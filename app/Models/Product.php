<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description',
        'category_id', 'brand_id', 'supplier_id',
        'price', 'base_price', 'shipping_fee', 'insurance_fee',
        'interest_rate', 'stock_quantity', 'sku',
        'thumbnail', 'status', 'featured', 'sort_order', 'specifications'
    ];

    protected $casts = [
        'specifications' => 'array',
        'featured' => 'boolean',
        'price' => 'decimal:2',
        'base_price' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orders()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    public function installmentPlans()
    {
        return $this->belongsToMany(InstallmentPlan::class, 'product_installment_plan');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function feeOverrides()
    {
        return $this->hasMany(ProductFeeOverride::class);
    }
}
