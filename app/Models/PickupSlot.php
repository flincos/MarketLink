<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupSlot extends Model
{
    protected $fillable = [
        'farmer_profile_id',
        'market_id',
        'date',
        'start_time',
        'end_time',
        'capacity',
        'is_available',
    ];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean',
    ];

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(
            FarmerProfile::class,
            'farmer_profile_id'
        );
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }
}