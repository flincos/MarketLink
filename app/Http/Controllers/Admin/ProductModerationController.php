<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class ProductModerationController extends Controller
{
    public function index()
    {
        $products = Product::with('farmerProfile.user')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function hide(Product $product): RedirectResponse
    {
        $product->update(['is_hidden' => true]);

        return back()->with('status', 'Product hidden from customers.');
    }

    public function unhide(Product $product): RedirectResponse
    {
        $product->update(['is_hidden' => false]);

        return back()->with('status', 'Product made visible to customers.');
    }
}