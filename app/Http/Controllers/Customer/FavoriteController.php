<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\Favorite;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display the customer's saved products and farmers.
     */
    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with([
                'product.farmer',
                'product.category',
                'farmer',
                'market',
            ])
            ->get();

        $productFavorites = $favorites
            ->whereNotNull('product_id');

        $farmerFavorites = $favorites
            ->whereNotNull('farmer_profile_id');

        $marketFavorites = $favorites
            ->whereNotNull('market_id');

        return view('customer.favorites.index', compact(
            'productFavorites',
            'farmerFavorites',
            'marketFavorites'
        ));
    }

    /**
     * Add a product to favorites.
     */
    public function storeProduct(Request $request, Product $product): RedirectResponse
    {
        Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Product added to your favorites.');
    }

    /**
     * Remove a product from favorites.
     */
    public function destroyProduct(Request $request, Product $product): RedirectResponse
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success', 'Product removed from your favorites.');
    }

    /**
     * Add a farmer to favorites.
     */
    public function storeFarmer(Request $request, FarmerProfile $farmer): RedirectResponse
    {
        Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'farmer_profile_id' => $farmer->id,
        ]);

        return back()->with('success', 'Farmer added to your favorites.');
    }

    /**
     * Remove a farmer from favorites.
     */
    public function destroyFarmer(Request $request, FarmerProfile $farmer): RedirectResponse
    {
        Favorite::where('user_id', $request->user()->id)
            ->where('farmer_profile_id', $farmer->id)
            ->delete();

        return back()->with('success', 'Farmer removed from your favorites.');
    }

    /**
     * Save a market to the customer's favorites.
     */
    public function storeMarket(Market $market)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'market_id' => $market->id,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Market added to your favorites.');
    }

    /**
     * Remove a market from the customer's favorites.
     */
    public function destroyMarket(Market $market)
    {
        Favorite::where('user_id', auth()->id())
            ->where('market_id', $market->id)
            ->delete();

        return redirect()
            ->back()
            ->with('success', 'Market removed from your favorites.');
    }
}
