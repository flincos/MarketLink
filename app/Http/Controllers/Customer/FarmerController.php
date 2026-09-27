<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    /**
     * Display approved farmers for customer discovery.
     */
    public function index(Request $request)
    {
        $query = FarmerProfile::with('user')
            ->where('status', 'approved');

        // Search by stall name.
        if ($request->filled('search')) {
            $query->where(
                'stall_name',
                'like',
                '%'.$request->search.'%'
            );
        }

        // Filter by operating day.
        if ($request->filled('day')) {
            $query->whereJsonContains(
                'operating_days',
                $request->day
            );
        }

        $farmers = $query
            ->orderBy('stall_name')
            ->paginate(12)
            ->withQueryString();

        // Retrieve the farmers already favorited by this customer.
        $favoriteFarmerIds = Favorite::where(
            'user_id',
            $request->user()->id
        )
            ->whereNotNull('farmer_profile_id')
            ->pluck('farmer_profile_id')
            ->toArray();

        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        return view('customer.farmers.index', compact(
            'farmers',
            'favoriteFarmerIds',
            'days'
        ));
    }

    /**
     * Display an approved farmer's public profile and products.
     */
    public function show(Request $request, FarmerProfile $farmer)
    {
        // Only approved farmers can be viewed by customers.
        abort_unless($farmer->status === 'approved', 404);

        // Load the farmer's products and their categories.
        $farmer->load([
            'user',
            'products.category',
        ]);

        // Retrieve the products saved by the current customer.
        $favoriteProductIds = Favorite::where('user_id', $request->user()->id)
            ->whereNotNull('product_id')
            ->pluck('product_id')
            ->toArray();

        // Check whether the customer has favorited this farmer.
        $isFavorite = Favorite::where('user_id', $request->user()->id)
            ->where('farmer_profile_id', $farmer->id)
            ->exists();

        return view('customer.farmers.show', compact(
            'farmer',
            'favoriteProductIds',
            'isFavorite'
        ));
    }
}
