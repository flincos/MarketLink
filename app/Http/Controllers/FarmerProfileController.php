<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FarmerProfileController extends Controller
{
     public function create()
    {
        if (auth()->user()->farmerProfile) {
            return redirect()->route('farmer.dashboard');
        }

        return view('farmer.profile-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stall_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
        ]);

        auth()->user()->farmerProfile()->create($validated);

        return redirect()->route('farmer.dashboard')
            ->with('status', 'Profile submitted! Awaiting admin approval.');
    }
}
