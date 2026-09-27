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
        $totalFarmers = User::where('role', 'farmer')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalMarkets = Market::count();
        $totalOrders = Order::count();
        $pendingFarmers = FarmerProfile::where('status', 'pending')->count();
        $revenueTotal = Order::where('status', 'completed')->sum('total_amount');
        $recentOrders = Order::with(['customer', 'farmer'])->latest()->take(10)->get();
        $recentFarmers = FarmerProfile::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalFarmers',
            'totalCustomers',
            'totalMarkets',
            'totalOrders',
            'pendingFarmers',
            'revenueTotal',
            'recentOrders',
            'recentFarmers',
        ));
    }
}
