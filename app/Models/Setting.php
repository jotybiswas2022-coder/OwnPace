<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'email', 'phone', 'location',
        'store_name', 'store_description', 'currency', 'currency_symbol',
        'default_interest_rate', 'cancellation_fee_percentage',
        'delivery_threshold_percentage', 'insurance_enabled',
        'primary_color', 'accent_color', 'logo', 'favicon',
        'meta_description', 'meta_keywords', 'timezone',
        'default_gateway', 'gateway_config', 'smtp_settings',
        'sms_settings', 'notification_channels', 'registration_enabled', 'guest_checkout',
        'allow_topup_withdrawal', 'withdrawal_fee_percent', 'topup_bonus_percent'
    ];

    protected $casts = [
        'insurance_enabled' => 'boolean',
        'registration_enabled' => 'boolean',
        'guest_checkout' => 'boolean',
        'allow_topup_withdrawal' => 'boolean',
        'withdrawal_fee_percent' => 'decimal:2',
        'topup_bonus_percent' => 'decimal:2',
        'default_interest_rate' => 'decimal:2',
        'cancellation_fee_percentage' => 'decimal:2',
        'delivery_threshold_percentage' => 'decimal:2',
        'gateway_config' => 'array',
        'notification_channels' => 'array',
    ];
}
