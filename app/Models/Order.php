<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'installment_plan_id',
        'status', 'total_amount', 'base_amount', 'shipping_fee',
        'insurance_fee', 'interest_amount', 'grand_total',
        'paid_amount', 'remaining_amount', 'payment_type',
        'has_insurance', 'delivery_status', 'delivered_at',
        'delivery_address_id', 'cancellation_reason', 'cancellation_fee', 'notes',
        'promo_code_id', 'discount_amount',
        'delivery_proxy_user_id'
    ];

    protected $casts = [
        'has_insurance' => 'boolean',
        'total_amount' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class);
    }

    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(DeliveryAddress::class);
    }

    public function deliveryTrackings()
    {
        return $this->hasMany(DeliveryTracking::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * The registered store user assigned to receive this delivery (proxy).
     */
    public function deliveryProxyUser()
    {
        return $this->belongsTo(User::class, 'delivery_proxy_user_id');
    }

    /**
     * True once paid_amount reaches the delivery threshold share of the total.
     */
    public function isEligibleForShipping(): bool
    {
        $threshold = \App\Services\DeliveryStatusService::thresholdPercent();

        return \App\Services\MoneyService::percentOf($this->paid_amount, $this->grand_total) >= $threshold;
    }
}
