<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Market;
use Illuminate\Http\RedirectResponse;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $markets = Market::orderBy('name')->paginate(20);

        return view('admin.markets.index', compact('markets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.markets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'description' => ['nullable', 'string'],

            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'operating_days' => ['nullable', 'array'],
            'operating_days.*' => ['in:mon,tue,wed,thu,fri,sat,sun'],
            'opening_time' => ['nullable', 'date_format:H:i'],
            'closing_time' => ['nullable', 'date_format:H:i', 'after:opening_time'],
        ]);

        Market::create($validated);

        return redirect()
            ->route('admin.markets.index')
            ->with('status', 'Market created.');
    }

    public function edit(Market $market)
    {
        return view('admin.markets.edit', compact('market'));
    }

    public function update(Request $request, Market $market): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'address'     => 'required|string',
            'description' => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        Market::create($validated);
        // ...
        $market->update($validated);

        return redirect()
            ->route('admin.markets.index')
            ->with('status', 'Market updated.');
        $data = $request->all();

        $data['operating_days'] = $request->filled('operating_days')
            ? array_values($request->input('operating_days'))
            : null;

        $data['opening_time'] = $request->input('opening_time') ?: null;
        $data['closing_time'] = $request->input('closing_time') ?: null;    
    }

    public function destroy(Market $market): RedirectResponse
    {
        $market->delete();

        return back()->with('status', 'Market deleted.');
    }

    public function map()
{
    $markets = \App\Models\Market::whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get(['id', 'name', 'address', 'latitude', 'longitude']);

    $markers = $markets->map(function ($market) {
        return [
            'lat'   => (float) $market->latitude,
            'lng'   => (float) $market->longitude,
            'label' => $market->name . '<br>' . e($market->address),
        ];
    })->values()->toArray();

    return view('admin.markets.map', compact('markers'));
}
}
