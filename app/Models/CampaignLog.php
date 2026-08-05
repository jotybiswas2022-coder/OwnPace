<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    protected $fillable = [
        'campaign_id', 'user_id', 'channel',
        'email', 'phone', 'status', 'sent_at',
        'provider_message_id', 'opened_at', 'open_count', 'click_count', 'error',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clicks()
    {
        return $this->hasMany(CampaignLinkClick::class, 'campaign_log_id');
    }

    public function getChannelLabelAttribute(): string
    {
        return match ($this->channel) {
            'both' => 'Email + SMS',
            'email' => 'Email',
            'sms' => 'SMS',
            default => ucfirst((string) $this->channel),
        };
    }
}
