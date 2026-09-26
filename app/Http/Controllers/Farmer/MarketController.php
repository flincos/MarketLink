<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmerProfile;

        $markets = $farmer->markets()
            ->latest()
            ->get();

        return view('farmer.markets.index', compact('markets'));
    }

    public function create()
    {
        $markets = Market::orderBy('name')->get();

        return view('farmer.markets.create', compact('markets'));
    }

    public function store(Request $request)
    {
        $farmer = auth()->user()->farmerProfile;

        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
        ]);

        if ($farmer->markets()->where('market_id', $validated['market_id'])->exists()) {
            return back()
                ->withErrors(['market_id' => 'This market is already associated with your profile.'])
                ->withInput();
        }

        $farmer->markets()->attach($validated['market_id']);

        return redirect()
            ->route('farmer.markets.index')
            ->with('success', 'Market added successfully.');
    }

    public function destroy(Market $market)
    {
        $farmer = auth()->user()->farmerProfile;

        $farmer->markets()->detach($market->id);

        return redirect()
            ->route('farmer.markets.index')
            ->with('success', 'Market removed successfully.');
    }
}