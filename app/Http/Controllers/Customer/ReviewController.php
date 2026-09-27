<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Submit a product review.
     * Customer must have a completed order containing the product.
     * One review per product per customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $customerId = auth()->id();

        // Ensure the customer has a completed order containing this product
        $hasCompletedOrder = Order::where('customer_id', $customerId)
            ->where('status', 'completed')
            ->whereHas('items', function ($query) use ($validated) {
                $query->where('product_id', $validated['product_id']);
            })
            ->exists();

        if (! $hasCompletedOrder) {
            return back()->withErrors([
                'review' => 'You can only review products from completed orders.',
            ]);
        }

        // Prevent duplicate reviews
        $alreadyReviewed = Review::where('customer_id', $customerId)
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($alreadyReviewed) {
            return back()->withErrors([
                'review' => 'You have already reviewed this product.',
            ]);
        }

        Review::create([
            'customer_id' => $customerId,
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Your review has been submitted. Thank you!');
    }
}
