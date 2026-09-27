<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PickupSlot;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * List all orders for the logged-in customer, paginated.
     */
    public function index()
    {
        $orders = Order::with(['farmer', 'market', 'items'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show the pre-order form for a specific product.
     * Requires ?product_id= query parameter.
     */
    public function create(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::with(['farmer.pickupSlots.market'])
            ->findOrFail($request->product_id);

        // Only show available pickup slots belonging to this farmer
        $pickupSlots = PickupSlot::with('market')
            ->where('farmer_profile_id', $product->farmer_profile_id)
            ->where('is_available', true)
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('customer.orders.create', compact('product', 'pickupSlots'));
    }

    /**
     * Place (store) a new order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'pickup_slot_id' => 'required|exists:pickup_slots,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock_quantity < $validated['quantity']) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Not enough stock. Only '.$product->stock_quantity.' available.']);
        }

        $pickupSlot = PickupSlot::findOrFail($validated['pickup_slot_id']);

        $subtotal = $product->price * $validated['quantity'];

        DB::transaction(function () use ($product, $pickupSlot, $validated, $subtotal) {
            $order = Order::create([
                'customer_id' => auth()->id(),
                'farmer_profile_id' => $product->farmer_profile_id,
                'market_id' => $pickupSlot->market_id,
                'pickup_date' => $pickupSlot->date,
                'pickup_time' => $pickupSlot->start_time,
                'total_amount' => $subtotal,
                'status' => 'placed',
                'notes' => $validated['notes'] ?? null,
            ]);

           $order = Order::create([
    'customer_id' => auth()->id(),
    'farmer_profile_id' => $product->farmer_profile_id,
    'market_id' => $pickupSlot->market_id,
    'pickup_slot_id' => $pickupSlot->id,
    'pickup_date' => $pickupSlot->date,
    'pickup_time' => $pickupSlot->start_time,
    'total_amount' => $subtotal,
    'status' => 'placed',
    'notes' => $validated['notes'] ?? null,
]);

            // Decrement product stock
            $product->decrement('stock_quantity', $validated['quantity']);
        });

        return redirect()
            ->route('customer.orders.index')
            ->with('success', 'Your order has been placed successfully!');
    }

    /**
     * Show a single order's detail page.
     */
    public function show(Order $order)
    {
        abort_if($order->customer_id !== auth()->id(), 403);

        $order->load(['farmer', 'market', 'items.product']);

        // Collect product IDs in this order
        $productIds = $order->items->pluck('product_id')->filter()->unique();

        // Find reviews already left by this customer for those products
        $existingReviews = Review::where('customer_id', auth()->id())
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->flip(); // keyed by product_id for easy lookup

        return view('customer.orders.show', compact('order', 'existingReviews'));
    }

    /**
     * Cancel an order (only if status is 'placed').
     */
    public function cancel(Order $order)
    {
        abort_if($order->customer_id !== auth()->id(), 403);

        if ($order->status !== 'placed') {
            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'Only orders with status "placed" can be cancelled.');
        }

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product_id) {
                Product::where('id', $item->product_id)
                    ->increment('stock_quantity', $item->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        return redirect()
            ->route('customer.orders.index')
            ->with('success', 'Your order has been cancelled.');
    }
}
