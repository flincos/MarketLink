<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Show the current customer's cart.
     */
    public function index()
    {
        $cart = session('customer_cart', []);

        if (empty($cart)) {
            return view('customer.cart.index', [
                'items' => collect(),
                'total' => 0,
            ]);
        }

        $products = Product::with(['farmer', 'category'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect($cart)->map(function ($quantity, $productId) use ($products) {
            $product = $products->get((int) $productId);

            if (! $product) {
                return null;
            }

            $quantity = (int) $quantity;

            return [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ];
        })->filter();

        $total = $items->sum('subtotal');

        return view('customer.cart.index', compact('items', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('farmer')->findOrFail($validated['product_id']);

        if (! $product->is_available || $product->is_hidden) {
            return back()
                ->withInput()
                ->withErrors([
                    'product_id' => 'This product is not currently available.',
                ]);
        }

        if (! $product->farmer || $product->farmer->status !== 'approved') {
            return back()
                ->withInput()
                ->withErrors([
                    'product_id' => 'This farmer is not currently approved.',
                ]);
        }

        if ($product->stock_quantity < 1) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => 'This product is currently out of stock.',
                ]);
        }

        $cart = session('customer_cart', []);

        if (! empty($cart)) {
            $cartProductIds = array_keys($cart);

            $existingFarmerIds = Product::query()
                ->whereIn('id', $cartProductIds)
                ->pluck('farmer_profile_id')
                ->unique();

            if (
                $existingFarmerIds->isNotEmpty()
                && $existingFarmerIds->contains(
                    fn ($farmerId) => (int) $farmerId !== (int) $product->farmer_profile_id
                )
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'product_id' => 'Your cart can only contain products from one farmer at a time.',
                    ]);
            }
        }

        $currentQuantity = (int) ($cart[$product->id] ?? 0);
        $newQuantity = $currentQuantity + (int) $validated['quantity'];

        if ($newQuantity > $product->stock_quantity) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => 'Only '.$product->stock_quantity.' units are currently available.',
                ]);
        }

        $cart[$product->id] = $newQuantity;

        session()->put('customer_cart', $cart);

        return redirect()
            ->route('customer.cart.index')
            ->with('success', 'Product added to cart.');
    }

    /**
     * Update a product quantity in the cart.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session('customer_cart', []);

        if (! array_key_exists($product->id, $cart)) {
            return redirect()
                ->route('customer.cart.index')
                ->withErrors([
                    'quantity' => 'This product is not in your cart.',
                ]);
        }

        if (! $product->is_available || $product->is_hidden) {
            return redirect()
                ->route('customer.cart.index')
                ->withErrors([
                    'quantity' => 'This product is no longer available.',
                ]);
        }

        if (! $product->farmer || $product->farmer->status !== 'approved') {
            return redirect()
                ->route('customer.cart.index')
                ->withErrors([
                    'quantity' => 'This farmer is not currently approved.',
                ]);
        }

        if ($validated['quantity'] > $product->stock_quantity) {
            return redirect()
                ->route('customer.cart.index')
                ->withErrors([
                    'quantity' => 'Only '.$product->stock_quantity.' units are currently available.',
                ]);
        }

        $cart[$product->id] = (int) $validated['quantity'];

        session()->put('customer_cart', $cart);

        return redirect()
            ->route('customer.cart.index')
            ->with('success', 'Cart quantity updated.');
    }

    /**
     * Remove a product from the cart.
     */
    public function destroy(Product $product)
    {
        $cart = session('customer_cart', []);

        unset($cart[$product->id]);

        session()->put('customer_cart', $cart);

        return redirect()
            ->route('customer.cart.index')
            ->with('success', 'Product removed from cart.');
    }

    /**
     * Empty the cart.
     */
    public function clear()
    {
        session()->forget('customer_cart');

        return redirect()
            ->route('customer.cart.index')
            ->with('success', 'Cart cleared.');
    }
}