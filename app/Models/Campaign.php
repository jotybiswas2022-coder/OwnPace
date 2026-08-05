<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name', 'subject', 'content', 'channel', 'audience',
        'recipient_filters', 'status', 'scheduled_at', 'sent_at',
        'template_id',
    ];

    protected $casts = [
        'recipient_filters' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function logs()
    {
        return $this->hasMany(CampaignLog::class);
    }

    public function template()
    {
        return $this->belongsTo(CampaignTemplate::class, 'template_id');
    }

    // ===== DELIVERY METRICS =====

    public function sentLogs()
    {
        return $this->logs()->whereIn('status', ['sent', 'delivered']);
    }

    public function failedLogs()
    {
        return $this->logs()->where('status', 'failed');
    }

    public function openedLogs()
    {
        return $this->logs()->whereNotNull('opened_at');
    }

    public function getDeliveredCountAttribute(): int
    {
        return $this->sentLogs()->count();
    }

    public function getFailedCountAttribute(): int
    {
        return $this->failedLogs()->count();
    }

    public function getOpenedCountAttribute(): int
    {
        return $this->openedLogs()->count();
    }

    public function getClickedCountAttribute(): int
    {
        return (int) $this->logs()->sum('click_count');
    }

    public function getOpenRateAttribute(): float
    {
        $delivered = $this->delivered_count;

        return $delivered > 0 ? round($this->opened_count / $delivered * 100, 1) : 0;
    }

    public function getClickRateAttribute(): float
    {
        $delivered = $this->delivered_count;

        return $delivered > 0 ? round($this->clicked_count / $delivered * 100, 1) : 0;
    }

    /**
     * Human label for the audience/segment selection.
     */
    public function getAudienceLabelAttribute(): string
    {
        return match ($this->audience) {
            'active_users' => 'Active Customers',
            'overdue_users' => 'Customers with overdue payments',
            'plan_users' => 'Customers on a plan',
            'repeat_customers' => 'Repeat customers',
            default => 'All Customers',
        };
    }
}
