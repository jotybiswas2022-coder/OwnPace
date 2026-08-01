<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id', 'user_id', 'amount', 'balance_before',
        'balance_after', 'type', 'description', 'reference', 'status',
        'withdrawable'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'withdrawable' => 'boolean',
    ];

    /**
     * Transaction types that add spendable money to the wallet.
     */
    public const CREDIT_TYPES = ['deposit', 'refund', 'cashback', 'bonus', 'adjustment', 'store_credit'];

    public function scopeCredits($query)
    {
        return $query->whereIn('type', self::CREDIT_TYPES);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
