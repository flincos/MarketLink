<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmerProfile;

        $reviews = Review::whereHas('product', function ($query) use ($farmer) {
                $query->where('farmer_profile_id', $farmer->id);
            })
            ->with(['user', 'product'])
            ->latest()
            ->get();

        return view('farmer.reviews.index', compact('reviews'));
    }

    public function respond(Request $request, Review $review)
    {
        $farmer = auth()->user()->farmerProfile;

        $belongsToFarmer = $review->product()
            ->where('farmer_profile_id', $farmer->id)
            ->exists();

        abort_unless($belongsToFarmer, 403);

        $validated = $request->validate([
            'farmer_response' => 'required|string|max:1000',
        ]);

        $review->update([
            'farmer_response' => $validated['farmer_response'],
        ]);

        return redirect()
            ->route('farmer.reviews.index')
            ->with('success', 'Response added successfully.');
    }
}