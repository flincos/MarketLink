<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $farmer = auth()->user()->farmerProfile;

        return view('farmer.profile', compact('farmer'));
    }

    public function update(Request $request)
    {
        $farmer = auth()->user()->farmerProfile;

        $validated = $request->validate([
            'stall_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_number' => 'required|string|max:30',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'description' => 'nullable|string',
            'operating_days' => 'nullable|array',
            'operating_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'order_cutoff_time' => 'nullable|date_format:H:i',
        ]);

        $farmer->update($validated);

        return redirect()
            ->route('farmer.profile')
            ->with('success', 'Profile updated successfully.');
    }
}