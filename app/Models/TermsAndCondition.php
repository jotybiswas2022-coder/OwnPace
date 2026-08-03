<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    protected $fillable = ['title', 'content', 'type', 'is_active', 'installment_plan_id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The installment plan these terms are scoped to (null = global terms).
     */
    public function installmentPlan()
    {
        return $this->belongsTo(InstallmentPlan::class);
    }

    /**
     * Global terms — apply to every order regardless of plan.
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('installment_plan_id');
    }

    /**
     * Terms that apply to a specific installment plan.
     */
    public function scopeForPlan($query, $planId)
    {
        return $query->where('installment_plan_id', $planId);
    }
}
