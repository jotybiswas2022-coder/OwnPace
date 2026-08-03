<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    protected $fillable = [
        'user_id', 'product_name', 'description', 'product_url', 'reason',
        'expected_price', 'brand_preference', 'category_preference', 'status',
        'admin_notes', 'approved_at'
    ];

    protected $casts = [
        'expected_price' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
