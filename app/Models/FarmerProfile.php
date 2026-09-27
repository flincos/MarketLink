<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class FarmerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'stall_name',
        'contact_person',
        'contact_number',
        'address',
        'latitude',
        'longitude',
        'description',
        'operating_days',
        'order_cutoff_time',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markets(): BelongsToMany
    {
        return $this->belongsToMany(Market::class, 'market_farmer');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'farmer_profile_id');
    }

    public function pickupSlots(): HasMany
    {
        return $this->hasMany(PickupSlot::class, 'farmer_profile_id');

    }

    protected $casts = [
        'operating_days' => 'array',
    ];

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'farmer_profile_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'farmer_profile_id');
    }

    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Product::class, 'farmer_profile_id', 'product_id');
    }
}
