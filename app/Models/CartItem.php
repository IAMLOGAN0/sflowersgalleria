<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'qty', 'price',
        'variants', 'variants_total', 'image', 'slug'
    ];

    protected $casts = [
        'variants' => 'array',
    ];
}

