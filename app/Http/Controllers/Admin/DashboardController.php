<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmerProfile;
use App\Models\User;
use App\Models\Market;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalFarmers   = FarmerProfile::count();
        $pendingFarmers = FarmerProfile::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalMarkets   = Market::count();
        $totalOrders    = Order::count();

        return view('admin.dashboard', compact(
            'totalFarmers',
            'pendingFarmers',
            'totalCustomers',
            'totalMarkets',
            'totalOrders'
        ));
    }
}
