<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\FarmerProfile;

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
    return $this->hasMany(Favorite::class);
}

public function reviews(): HasMany
{
    return $this->hasMany(Review::class);
}

public function farmerProfile()
{
    return $this->belongsTo(FarmerProfile::class);
}
    
}