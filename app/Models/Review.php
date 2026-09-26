<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
    'customer_id',
    'product_id',
    'rating',
    'comment',
    'farmer_response',
];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function customer()
{
    return $this->belongsTo(User::class, 'customer_id');
}
}