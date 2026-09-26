<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $farmer = auth()->user()->farmerProfile;

        $products = $farmer->products()
            ->with('category')
            ->latest()
            ->get();

        return view('farmer.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('farmer.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $farmer = auth()->user()->farmerProfile;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock_quantity' => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');

        $farmer->products()->create($validated);

        return redirect()
            ->route('farmer.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $product->farmer_profile_id === $farmer->id,
            403
        );

        $categories = Category::orderBy('name')->get();

        return view('farmer.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $product->farmer_profile_id === $farmer->id,
            403
        );

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'stock_quantity' => 'required|numeric|min:0',
            'is_available' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');

        $product->update($validated);

        return redirect()
            ->route('farmer.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $farmer = auth()->user()->farmerProfile;

        abort_unless(
            $product->farmer_profile_id === $farmer->id,
            403
        );

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('farmer.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}