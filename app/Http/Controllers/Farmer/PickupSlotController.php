<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\PickupSlot;
use Illuminate\Http\Request;

class PickupSlotController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmerProfile;

        $slots = $farmer->pickupSlots()
            ->with('market')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('farmer.pickup-slots.index', compact('slots'));
    }

    public function create()
    {
        $farmer = auth()->user()->farmerProfile;

        $markets = $farmer->markets()
            ->orderBy('name')
            ->get();

        return view('farmer.pickup-slots.create', compact('markets'));
    }

    public function store(Request $request)
    {
        $farmer = auth()->user()->farmerProfile;

        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'nullable|boolean',
        ]);

        $marketBelongsToFarmer = $farmer->markets()
            ->where('markets.id', $validated['market_id'])
            ->exists();

        abort_unless($marketBelongsToFarmer, 403);

        $validated['is_available'] = $request->boolean('is_available');

        $farmer->pickupSlots()->create($validated);

        return redirect()
            ->route('farmer.pickup-slots.index')
            ->with('success', 'Pickup slot created successfully.');
    }

    public function edit(PickupSlot $pickupSlot)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $pickupSlot->farmer_profile_id === $farmer->id,
            403
        );

        $markets = $farmer->markets()
            ->orderBy('name')
            ->get();

        return view(
            'farmer.pickup-slots.edit',
            compact('pickupSlot', 'markets')
        );
    }

    public function update(
        Request $request,
        PickupSlot $pickupSlot
    ) {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $pickupSlot->farmer_profile_id === $farmer->id,
            403
        );

        $validated = $request->validate([
            'market_id' => 'required|exists:markets,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'is_available' => 'nullable|boolean',
        ]);

        $marketBelongsToFarmer = $farmer->markets()
            ->where('markets.id', $validated['market_id'])
            ->exists();

        abort_unless($marketBelongsToFarmer, 403);

        $validated['is_available'] = $request->boolean('is_available');

        $pickupSlot->update($validated);

        return redirect()
            ->route('farmer.pickup-slots.index')
            ->with('success', 'Pickup slot updated successfully.');
    }

    public function destroy(PickupSlot $pickupSlot)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $pickupSlot->farmer_profile_id === $farmer->id,
            403
        );

        $pickupSlot->delete();

        return redirect()
            ->route('farmer.pickup-slots.index')
            ->with('success', 'Pickup slot deleted successfully.');
    }
}