<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

    $productFavorites = Favorite::where('user_id', $user->id)
        ->whereNotNull('product_id')
        ->with('product')
        ->latest()
        ->take(4)
        ->get();

    $marketFavorites = collect(); // or new Collection();

    $unreadNotificationsCount = $user
        ? $user->unreadNotifications()->count()
        : 0;

    return view('customer.dashboard', compact(
        'productFavorites',
        'marketFavorites',
        'unreadNotificationsCount'
    ));
    }
}
