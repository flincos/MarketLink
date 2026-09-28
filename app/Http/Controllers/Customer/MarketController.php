<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    /**
     * Display a listing of markets.
     */
    public function index(Request $request)
    {
        $query = Market::with([
            'farmers' => function ($query) {
                $query->where('status', 'approved');
            },
        ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('address', 'like', '%'.$search.'%');
            });
        }

        $markets = $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $favoriteMarketIds = auth()->user()
            ->favorites()
            ->whereNotNull('market_id')
            ->pluck('market_id')
            ->toArray();

        return view('customer.markets.index', compact(
            'markets',
            'favoriteMarketIds'
        ));
    }

    /**
     * Display the specified market.
     */
    public function show(Market $market)
    {
        $market->load([
            'farmers' => function ($query) {
                $query->where('status', 'approved')
                    ->with('user');
            },
        ]);

        return view('customer.markets.show', compact('market'));
    }

    public function setPreferred(Market $market, Request $request)
    {
        $user = $request->user();
        $user->preferred_market_id = $market->id;
        $user->save();

        return back()->with('success', 'Preferred market updated.');
    }

    public function clearPreferred(Request $request)
    {
        $user = $request->user();
        $user->preferred_market_id = null;
        $user->save();

        return back()->with('success', 'Preferred market cleared.');
    }
}
