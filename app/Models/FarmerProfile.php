<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    return $this->belongsToMany(Market::class);
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

}