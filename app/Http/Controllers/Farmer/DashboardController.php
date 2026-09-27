<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmerProfile;

        if (! $farmer) {
            return redirect()->route('farmer.profile.create');
        }

        $totalProducts = $farmer->products()->count();
        $activeOrders = $farmer->orders()->whereIn('status', ['placed', 'accepted', 'ready'])->count();
        $totalMarkets = $farmer->markets()->count();
        $totalReviews = Review::whereHas('product', fn ($q) => $q->where('farmer_profile_id', $farmer->id))->count();
        $totalOrders = $farmer->orders()->count();
        $pendingOrders = $farmer->orders()->where('status', 'placed')->count();
        $revenue = $farmer->orders()->where('status', 'completed')->sum('total_amount');
        $recentOrders = $farmer->orders()
            ->with(['customer', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('farmer.dashboard', compact(
            'farmer',
            'totalProducts',
            'activeOrders',
            'totalMarkets',
            'totalReviews',
            'totalOrders',
            'pendingOrders',
            'revenue',
            'recentOrders',
        ));
    }
}
