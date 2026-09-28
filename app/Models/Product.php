<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'farmer_profile_id',
        'category_id',
        'name',
        'description',
        'price',
        'unit',
        'stock_quantity',
        'is_available',
        'image',
        'is_hidden',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_hidden' => 'boolean',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(FarmerProfile::class, 'farmer_profile_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    protected static function booted()
    {
    static::updated(function (Product $product) {
        // Determine previous "availability" and current "availability"
        $wasAvailable = self::availabilityFlag($product->getOriginal());
        $isAvailable  = self::availabilityFlag($product->getAttributes());

        if (! $wasAvailable && $isAvailable) {
            $product->notifyFavoritesOfRestock();
        }

        if ($wasAvailable && ! $isAvailable) {
        $product->favorites()->update(['restock_notified_at' => null]);
        }
    });
    }
    protected static function availabilityFlag(array $attributes): bool
    {
        $available = $attributes['is_available'] ?? false;
        $stock     = $attributes['stock_quantity'] ?? 0;

        return (bool) $available && (int) $stock > 0;
    }

    public function notifyFavoritesOfRestock(): void
    {
        // Get favorites where this product is favorited and not yet notified for this restock
        $favorites = $this->favorites()
            ->whereNull('restock_notified_at')
            ->get();

        if ($favorites->isEmpty()) {
            return;
        }

        foreach ($favorites as $favorite) {
            $user = $favorite->user;
            if (! $user) {
                continue;
            }

            $user->notify(new \App\Notifications\ProductRestocked($this));

            // Mark as notified
            $favorite->restock_notified_at = now();
            $favorite->save();
        }
    }
}