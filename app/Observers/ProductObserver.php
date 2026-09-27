<?php

namespace App\Observers;

use App\Models\Product;
use App\Notifications\ProductRestockedNotification;

class ProductObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Check whether the stock quantity was changed.
        if (! $product->wasChanged('stock_quantity')) {
            return;
        }

        // Only notify customers when stock goes from zero or less to above zero.
        $previousStock = (int) $product->getOriginal('stock_quantity');
        $currentStock = (int) $product->stock_quantity;

        if ($previousStock > 0 || $currentStock <= 0) {
            return;
        }

        // Find customers who favorited this product.
        $favorites = $product->favorites()
            ->with('user')
            ->get();

        foreach ($favorites as $favorite) {
            if ($favorite->user) {
                $favorite->user->notify(
                    new ProductRestockedNotification($product)
                );
            }
        }
    }
}
