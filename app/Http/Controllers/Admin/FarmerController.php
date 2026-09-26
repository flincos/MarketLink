<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmerProfile;
use Illuminate\Http\RedirectResponse;

class FarmerController extends Controller
{
    public function index()
    {
        $farmers = FarmerProfile::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.farmers.index', compact('farmers'));
    }

    public function approve(FarmerProfile $farmerProfile): RedirectResponse
    {
        $farmerProfile->update(['status' => 'approved']);

        return back()->with('status', 'Farmer approved successfully.');
    }

    public function suspend(FarmerProfile $farmerProfile): RedirectResponse
    {
        $farmerProfile->update(['status' => 'suspended']);

        return back()->with('status', 'Farmer suspended successfully.');
    }
}
