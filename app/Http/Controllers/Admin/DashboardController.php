<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\Market;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFarmers = FarmerProfile::count();
        $pendingFarmers = FarmerProfile::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalMarkets = Market::count();
        $totalOrders = Order::count();

        $revenueTotal = Order::where('status', 'completed')
            ->sum('total_amount');

        $marketRevenue = Order::query()
            ->where('status', 'completed')
            ->selectRaw('market_id, SUM(total_amount) as revenue')
            ->with('market')
            ->groupBy('market_id')
            ->orderByDesc('revenue')
            ->get();

        $activeFarmers = FarmerProfile::query()
            ->with('user')
            ->withCount('orders')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get();

        $recentOrders = Order::with(['customer', 'farmer'])
            ->latest()
            ->take(10)
            ->get();

        $recentFarmers = FarmerProfile::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalFarmers',
            'pendingFarmers',
            'totalCustomers',
            'totalMarkets',
            'totalOrders',
            'revenueTotal',
            'marketRevenue',
            'activeFarmers',
            'recentOrders',
            'recentFarmers',
        ));
    }
}