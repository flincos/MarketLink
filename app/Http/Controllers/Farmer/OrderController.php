<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Order;

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

    public function updateStatus(\Illuminate\Http\Request $request, Order $order)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $order->farmer_profile_id === $farmer->id,
            403
        );

        $validated = $request->validate([
            'status' => 'required|in:accepted,declined,ready,completed',
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('farmer.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}