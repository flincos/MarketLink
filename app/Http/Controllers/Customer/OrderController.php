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
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * List all orders for the logged-in customer, paginated.
     */
    public function index()
    {
        $orders = Order::with(['farmer', 'market', 'pickupSlot', 'items'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show the checkout form for the current cart.
     */
    public function create()
    {
        $cart = session('customer_cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('customer.cart.index')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        $productIds = array_map('intval', array_keys($cart));

        $products = Product::with('farmer')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        if ($products->count() !== count($productIds)) {
            return redirect()
                ->route('customer.cart.index')
                ->withErrors([
                    'cart' => 'One or more products in your cart are no longer available.',
                ]);
        }

        $farmerIds = $products->pluck('farmer_profile_id')->unique();

        if ($farmerIds->count() !== 1) {
            return redirect()
                ->route('customer.cart.index')
                ->withErrors([
                    'cart' => 'Your cart can only contain products from one farmer.',
                ]);
        }

        $farmer = $products->first()->farmer;

        if (! $farmer || $farmer->status !== 'approved') {
            return redirect()
                ->route('customer.cart.index')
                ->withErrors([
                    'cart' => 'This farmer is not currently approved.',
                ]);
        }

        $pickupSlots = PickupSlot::with('market')
            ->where('farmer_profile_id', $farmer->id)
            ->where('is_available', true)
            ->where('date', '>=', today())
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('customer.orders.create', compact(
            'products',
            'cart',
            'pickupSlots',
            'farmer'
        ));
    }

    /**
     * Place an order from the customer's cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup_slot_id' => 'required|exists:pickup_slots,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = session('customer_cart', []);

        if (empty($cart)) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        DB::transaction(function () use ($validated, $cart) {
            $productIds = array_map('intval', array_keys($cart));

            $products = Product::with('farmer')
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== count($productIds)) {
                throw ValidationException::withMessages([
                    'cart' => 'One or more products in your cart are no longer available.',
                ]);
            }

            $farmerIds = $products
                ->pluck('farmer_profile_id')
                ->unique();

            if ($farmerIds->count() !== 1) {
                throw ValidationException::withMessages([
                    'cart' => 'Your cart can only contain products from one farmer.',
                ]);
            }

            $farmer = $products->first()->farmer;

            if (! $farmer || $farmer->status !== 'approved') {
                throw ValidationException::withMessages([
                    'cart' => 'This farmer is not currently approved.',
                ]);
            }

            foreach ($products as $product) {
                $quantity = (int) ($cart[$product->id] ?? 0);

                if ($quantity < 1) {
                    throw ValidationException::withMessages([
                        'cart' => 'Your cart contains an invalid quantity.',
                    ]);
                }

                if (! $product->is_available || $product->is_hidden) {
                    throw ValidationException::withMessages([
                        'cart' => $product->name . ' is no longer available.',
                    ]);
                }

                if ($product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => 'Not enough stock for ' . $product->name .
                            '. Only ' . $product->stock_quantity . ' available.',
                    ]);
                }
            }

            $pickupSlot = PickupSlot::query()
                ->lockForUpdate()
                ->findOrFail($validated['pickup_slot_id']);

            if (! $pickupSlot->is_available) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'This pickup slot is not available.',
                ]);
            }

            if ($pickupSlot->date->isBefore(today())) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'This pickup slot is no longer available.',
                ]);
            }

            if ($pickupSlot->farmer_profile_id !== $farmer->id) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'The selected pickup slot does not belong to this farmer.',
                ]);
            }

            $marketBelongsToFarmer = $farmer->markets()
                ->whereKey($pickupSlot->market_id)
                ->exists();

            if (! $marketBelongsToFarmer) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'The selected pickup market is not associated with this farmer.',
                ]);
            }

            if (
                $pickupSlot->date->isToday()
                && $farmer->order_cutoff_time
                && now()->format('H:i:s') >= $farmer->order_cutoff_time
            ) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'The farmer\'s order cutoff time has passed.',
                ]);
            }

            $activeOrders = Order::query()
                ->where('pickup_slot_id', $pickupSlot->id)
                ->whereIn('status', ['placed', 'accepted', 'ready'])
                ->count();

            if ($activeOrders >= $pickupSlot->capacity) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'This pickup slot is full.',
                ]);
            }

            $totalAmount = 0;

            foreach ($products as $product) {
                $quantity = (int) $cart[$product->id];
                $totalAmount += $product->price * $quantity;
            }

            $order = Order::create([
                'customer_id' => auth()->id(),
                'farmer_profile_id' => $farmer->id,
                'market_id' => $pickupSlot->market_id,
                'pickup_slot_id' => $pickupSlot->id,
                'pickup_date' => $pickupSlot->date,
                'pickup_time' => $pickupSlot->start_time,
                'total_amount' => $totalAmount,
                'status' => 'placed',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($products as $product) {
                $quantity = (int) $cart[$product->id];
                $subtotal = $product->price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock_quantity', $quantity);
            }

            session()->forget('customer_cart');
        });

        return redirect()
            ->route('customer.orders.index')
            ->with('success', 'Your order has been placed successfully!');
    }

    /**
     * Add products from a completed order back into the customer's cart.
     */
    public function reorder(Order $order)
    {
        abort_if($order->customer_id !== auth()->id(), 403);

        if ($order->status !== 'completed') {
            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'Only completed orders can be reordered.');
        }

        $order->load([
            'items.product.farmer',
        ]);

        if ($order->items->isEmpty()) {
            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'This order has no products to reorder.');
        }

        $farmer = $order->items->first()->product?->farmer;

        if (! $farmer || $farmer->status !== 'approved') {
            return redirect()
                ->route('customer.orders.index')
                ->with('error', 'The farmer from this order is not currently approved.');
        }

        $cart = session('customer_cart', []);

        if (! empty($cart)) {
            $cartProductIds = array_map('intval', array_keys($cart));

            $cartProducts = Product::whereIn('id', $cartProductIds)
                ->get(['id', 'farmer_profile_id']);

            if ($cartProducts->pluck('farmer_profile_id')->unique()->contains(
                fn ($farmerId) => (int) $farmerId !== (int) $farmer->id
            )) {
                return redirect()
                    ->route('customer.cart.index')
                    ->with('error', 'Your current cart contains products from another farmer.');
            }
        }

        $newCart = $cart;

        foreach ($order->items as $item) {
            $product = $item->product;

            if (! $product) {
                return redirect()
                    ->route('customer.orders.index')
                    ->with('error', 'One or more products from this order no longer exist.');
            }

            if (
                ! $product->is_available ||
                $product->is_hidden ||
                $product->farmer?->status !== 'approved'
            ) {
                return redirect()
                    ->route('customer.orders.index')
                    ->with('error', $product->name . ' is no longer available.');
            }

            $currentQuantity = (int) ($newCart[$product->id] ?? 0);
            $requestedQuantity = (int) $item->quantity;
            $newQuantity = $currentQuantity + $requestedQuantity;

            if ($newQuantity > $product->stock_quantity) {
                return redirect()
                    ->route('customer.orders.index')
                    ->with(
                        'error',
                        'Not enough stock for ' . $product->name .
                        '. Only ' . $product->stock_quantity . ' available.'
                    );
            }

            $newCart[$product->id] = $newQuantity;
        }

        session()->put('customer_cart', $newCart);

        return redirect()
            ->route('customer.cart.index')
            ->with('success', 'The items from your completed order have been added to your cart.');
    }

    /**
     * Show a single order's detail page.
     */
    public function show(Order $order)
    {
        abort_if($order->customer_id !== auth()->id(), 403);

        $order->load([
            'farmer',
            'market',
            'pickupSlot',
            'items.product',
        ]);

        $productIds = $order->items
            ->pluck('product_id')
            ->filter()
            ->unique();

        $existingReviews = Review::where('customer_id', auth()->id())
            ->whereIn('product_id', $productIds)
            ->pluck('product_id')
            ->flip();

        $pickupSlots = PickupSlot::with('market')
            ->where('farmer_profile_id', $order->farmer_profile_id)
            ->where('date', '>=', today())
            ->where(function ($query) use ($order) {
                $query->where('is_available', true)
                    ->orWhere('id', $order->pickup_slot_id);
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view(
            'customer.orders.show',
            compact('order', 'existingReviews', 'pickupSlots')
        );
    }

    /**
     * Modify an existing order before the farmer's cutoff time.
     */
    public function update(Request $request, Order $order)
    {
        abort_if($order->customer_id !== auth()->id(), 403);

        $validated = $request->validate([
            'pickup_slot_id' => 'required|exists:pickup_slots,id',
            'items' => 'required|array|min:1',
            'items.*.quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated, $order) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->customer_id !== auth()->id()) {
                abort(403);
            }

            if (! in_array($lockedOrder->status, ['placed', 'accepted'], true)) {
                throw ValidationException::withMessages([
                    'order' => 'This order can no longer be modified.',
                ]);
            }

            $lockedOrder->load([
                'items' => fn ($query) => $query->orderBy('product_id'),
                'farmer',
            ]);

            $pickupSlot = PickupSlot::query()
                ->lockForUpdate()
                ->findOrFail($validated['pickup_slot_id']);

            if (! $pickupSlot->is_available) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'This pickup slot is not available.',
                ]);
            }

            if ($pickupSlot->date->isBefore(today())) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'This pickup slot is no longer available.',
                ]);
            }

            if ($pickupSlot->farmer_profile_id !== $lockedOrder->farmer_profile_id) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'The selected pickup slot does not belong to this farmer.',
                ]);
            }

            $marketBelongsToFarmer = $lockedOrder->farmer
                ->markets()
                ->whereKey($pickupSlot->market_id)
                ->exists();

            if (! $marketBelongsToFarmer) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'The selected pickup market is not associated with this farmer.',
                ]);
            }

            if (
                $pickupSlot->date->isToday()
                && $lockedOrder->farmer->order_cutoff_time
                && now()->format('H:i:s') >= $lockedOrder->farmer->order_cutoff_time
            ) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'The farmer\'s order cutoff time has passed.',
                ]);
            }

            $itemsByProduct = $lockedOrder->items->keyBy('product_id');

            if ($itemsByProduct->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'This order has no modifiable items.',
                ]);
            }

            $totalAmount = 0;

            foreach ($itemsByProduct as $productId => $item) {
                $newQuantity = (int) ($validated['items'][$productId]['quantity'] ?? 0);

                $product = Product::query()
                    ->lockForUpdate()
                    ->find($productId);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'items' => 'One or more products in this order no longer exist.',
                    ]);
                }

                $oldQuantity = (int) $item->quantity;
                $difference = $newQuantity - $oldQuantity;

                if ($difference > 0 && $product->stock_quantity < $difference) {
                    throw ValidationException::withMessages([
                        'items.' . $productId . '.quantity' =>
                            'Not enough stock for ' . $product->name .
                            '. Only ' . $product->stock_quantity .
                            ' additional units are available.',
                    ]);
                }

                if ($newQuantity === 0) {
                    $item->delete();

                    if ($oldQuantity > 0) {
                        $product->increment('stock_quantity', $oldQuantity);
                    }

                    continue;
                }

                if ($difference > 0) {
                    $product->decrement('stock_quantity', $difference);
                } elseif ($difference < 0) {
                    $product->increment('stock_quantity', abs($difference));
                }

                $item->update([
                    'quantity' => $newQuantity,
                    'subtotal' => $item->price * $newQuantity,
                ]);

                $totalAmount += $item->price * $newQuantity;
            }

            if ($lockedOrder->items()->count() === 0) {
                throw ValidationException::withMessages([
                    'items' => 'An order must contain at least one product.',
                ]);
            }

            $activeOrders = Order::query()
                ->where('pickup_slot_id', $pickupSlot->id)
                ->where('id', '!=', $lockedOrder->id)
                ->whereIn('status', ['placed', 'accepted', 'ready'])
                ->count();

            if ($activeOrders >= $pickupSlot->capacity) {
                throw ValidationException::withMessages([
                    'pickup_slot_id' => 'This pickup slot is full.',
                ]);
            }

            $lockedOrder->update([
                'pickup_slot_id' => $pickupSlot->id,
                'market_id' => $pickupSlot->market_id,
                'pickup_date' => $pickupSlot->date,
                'pickup_time' => $pickupSlot->start_time,
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? $lockedOrder->notes,
            ]);
        });

        return redirect()
            ->route('customer.orders.show', $order)
            ->with('success', 'Your order has been updated successfully.');
    }

    /**
     * Cancel an order that is still in placed status.
     */
    public function cancel(Order $order)
    {
        abort_if($order->customer_id !== auth()->id(), 403);

        if ($order->status !== 'placed') {
            return redirect()
                ->route('customer.orders.show', $order)
                ->with('error', 'Only orders with status "placed" can be cancelled.');
        }

        DB::transaction(function () use ($order) {
            $order->load('items');

            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if (
                $lockedOrder->customer_id !== auth()->id()
                || $lockedOrder->status !== 'placed'
            ) {
                throw ValidationException::withMessages([
                    'order' => 'This order can no longer be cancelled.',
                ]);
            }

            $items = $lockedOrder->items;

            foreach ($items as $item) {
                if ($item->product_id) {
                    Product::query()
                        ->whereKey($item->product_id)
                        ->lockForUpdate()
                        ->first()?->increment(
                            'stock_quantity',
                            $item->quantity
                        );
                }
            }

            $lockedOrder->update([
                'status' => 'cancelled',
            ]);
        });

        return redirect()
            ->route('customer.orders.index')
            ->with('success', 'Your order has been cancelled.');
    }
}
