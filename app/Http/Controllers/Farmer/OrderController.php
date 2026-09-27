<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Notifications\OrderConfirmed;
use App\Notifications\OrderReadyForPickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmerProfile;

        $orders = $farmer->orders()
            ->with(['customer', 'market', 'items.product'])
            ->latest()
            ->get();

        return view('farmer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $order->farmer_profile_id === $farmer->id,
            403
        );

        $order->load([
            'customer',
            'market',
            'items.product'
        ]);

        return view('farmer.orders.show', compact('order'));
    }

    public function history()
    {
        $farmer = auth()->user()->farmerProfile;

        $orders = $farmer->orders()
            ->with(['customer', 'market', 'items.product'])
            ->whereIn('status', ['completed', 'declined'])
            ->latest()
            ->get();

        $totalOrders = $farmer->orders()->count();

        $pendingOrders = $farmer->orders()
            ->whereIn('status', ['placed', 'accepted', 'ready'])
            ->count();

        $revenue = $farmer->orders()
            ->where('status', 'completed')
            ->sum('total_amount');

        $bestSellingProducts = $farmer->orders()
            ->where('status', 'completed')
            ->with('items')
            ->get()
            ->flatMap(function ($order) {
                return $order->items;
            })
            ->groupBy('product_id')
            ->map(function ($items) {
                return [
                    'product_name' => $items->first()->product_name,
                    'quantity' => $items->sum('quantity'),
                ];
            })
            ->sortByDesc('quantity')
            ->values();

        return view('farmer.orders.history', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'revenue',
            'bestSellingProducts'
        ));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $order->farmer_profile_id === $farmer->id,
            403
        );

        $validated = $request->validate([
            'status' => 'required|in:accepted,declined,ready,completed',
        ]);

        $currentStatus = $order->status;
        $newStatus = $validated['status'];

        $allowedTransitions = [
            'placed' => ['accepted', 'declined'],
            'accepted' => ['ready'],
            'ready' => ['completed'],
        ];

        if (
            ! isset($allowedTransitions[$currentStatus]) ||
            ! in_array($newStatus, $allowedTransitions[$currentStatus], true)
        ) {
            return redirect()
                ->route('farmer.orders.show', $order)
                ->with('error', 'This order cannot be moved to that status.');
        }

        DB::transaction(function () use ($order, $newStatus) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            abort_unless(
                $lockedOrder->farmer_profile_id === auth()->user()->farmerProfile->id,
                403
            );

            $currentStatus = $lockedOrder->status;

            $allowedTransitions = [
                'placed' => ['accepted', 'declined'],
                'accepted' => ['ready'],
                'ready' => ['completed'],
            ];

            if (
                ! isset($allowedTransitions[$currentStatus]) ||
                ! in_array($newStatus, $allowedTransitions[$currentStatus], true)
            ) {
                throw ValidationException::withMessages([
                    'status' => 'This order status transition is no longer valid.',
                ]);
            }

            if ($newStatus === 'declined') {
                $lockedOrder->load('items');

                foreach ($lockedOrder->items as $item) {
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
            }

            $lockedOrder->update([
                'status' => $newStatus,
            ]);
        });

        $order->refresh();
        $order->load('customer');

        if ($order->customer) {
            if ($newStatus === 'accepted') {
                $order->customer->notify(
                    new OrderConfirmed($order)
                );
            }

            if ($newStatus === 'ready') {
                $order->customer->notify(
                    new OrderReadyForPickup($order)
                );
            }
        }

        return redirect()
            ->route('farmer.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}