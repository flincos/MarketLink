<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with([
            'farmer.user',
            'farmer.markets',
            'category',
        ])->whereHas('farmer', function ($q) {
            $q->where('status', 'approved');
        });

        // Search by product name
        if ($request->filled('search')) {
            $query->where(
                'name',
                'like',
                '%'.$request->search.'%'
            );
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by market
        if ($request->filled('market')) {
            $query->whereHas('farmer.markets', function ($q) use ($request) {
                $q->where('markets.id', $request->market);
            });
        }

        // Filter by operating day
        if ($request->filled('day')) {
            $query->whereHas('farmer', function ($q) use ($request) {
                $q->whereJsonContains('operating_days', $request->day);
            });
        }

        // Filter by availability
        if ($request->filled('availability')) {
            if ($request->availability === 'in_stock') {
                $query->where('is_available', true)
                    ->where('stock_quantity', '>', 0);
            } elseif ($request->availability === 'out_of_stock') {
                $query->where(function ($q) {
                    $q->where('is_available', false)
                        ->orWhere('stock_quantity', '<=', 0);
                });
            }
        }

        $products = $query
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        $markets = Market::orderBy('name')->get();

        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        $favoriteProductIds = auth()->user()
            ->favorites()
            ->whereNotNull('product_id')
            ->pluck('product_id')
            ->toArray();

        return view('customer.products.index', compact(
            'products',
            'categories',
            'markets',
            'days',
            'favoriteProductIds'
        ));
    }

    public function show(Product $product)
    {
        abort_unless(
            $product->farmer && $product->farmer->status === 'approved',
            404
        );

        $product->load([
            'farmer.user',
            'farmer.markets',
            'category',
        ]);

        $isFavorite = auth()->user()
            ->favorites()
            ->where('product_id', $product->id)
            ->exists();

        return view('customer.products.show', compact(
            'product',
            'isFavorite'
        ));
    }
}
