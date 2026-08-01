<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'balance', 'total_deposited',
        'total_withdrawn', 'cashback_earned', 'status'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_deposited' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'cashback_earned' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WalletWithdrawalRequest::class);
    }

    /**
     * Money that can be moved back to a bank account (subject to the fee).
     */
    public function withdrawableBalance(): float
    {
        return \App\Services\WalletService::withdrawableBalance($this);
    }
}
