<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewModerationController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'user']) // adjust relation names if needed
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function hide(Review $review): RedirectResponse
    {
        $review->update(['is_hidden' => true]);

        return back()->with('status', 'Review hidden from customers.');
    }

    public function unhide(Review $review): RedirectResponse
    {
        $review->update(['is_hidden' => false]);

        return back()->with('status', 'Review made visible to customers.');
    }
}
