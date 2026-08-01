<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletWithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id', 'wallet_id', 'bank_account_id',
        'amount', 'fee', 'net_amount', 'status',
        'admin_note', 'reference', 'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public const STATUSES = ['pending', 'processing', 'completed', 'failed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
