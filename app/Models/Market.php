<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Market extends Model
{
    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'description',
    ];

    public function farmers(): BelongsToMany
    {
        return $this->belongsToMany(FarmerProfile::class, 'market_farmer');
    }
}
