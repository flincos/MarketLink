<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Retrieve the customer's favorite products.
        $productFavorites = Favorite::where('user_id', $user->id)
            ->whereNotNull('product_id')
            ->with([
                'product.farmer',
                'product.category',
            ])
            ->latest()
            ->take(4)
            ->get();

        // Retrieve the customer's preferred markets.
        $marketFavorites = Favorite::where('user_id', $user->id)
            ->whereNotNull('market_id')
            ->with('market')
            ->latest()
            ->take(4)
            ->get();

        // Count unread notifications.
        $unreadNotificationsCount = $user
            ->unreadNotifications()
            ->count();

        return view('customer.dashboard', compact(
            'productFavorites',
            'marketFavorites',
            'unreadNotificationsCount'
        ));
    }
}
