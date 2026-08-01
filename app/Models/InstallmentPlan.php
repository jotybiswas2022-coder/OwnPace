<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    protected $fillable = [
        'name', 'type', 'duration', 'duration_days',
        'interest_rate', 'description', 'is_active', 'sort_order',
        'late_fee_enabled', 'late_fee_percent'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'interest_rate' => 'decimal:2',
        'late_fee_enabled' => 'boolean',
        'late_fee_percent' => 'decimal:2',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getPerInstallmentAmountAttribute($totalAmount)
    {
        return $totalAmount / $this->duration;
    }

    /**
     * Friendly cadence label, e.g. "Every week" / "Every month".
     */
    public function getCadenceAttribute(): string
    {
        return $this->type === 'weekly' ? 'Every week' : 'Every month';
    }
}
