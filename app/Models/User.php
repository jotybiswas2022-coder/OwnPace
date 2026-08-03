<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'phone', 'avatar',
        'nid_number', 'nid_image', 'identity_verification',
        'payment_card_verification', 'bank_account_verification',
        'delivery_address_verification', 'store_terms_acceptance',
        'is_admin', 'is_active', 'is_suspended', 'suspended_at', 'support_notes'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'is_suspended' => 'boolean',
        'suspended_at' => 'datetime',
    ];

    // ===== ROLES =====
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isSuperAdmin()
    {
        return $this->role_id == 1 || $this->is_admin == 1;
    }

    public function isAdmin()
    {
        return in_array($this->role_id, [1, 2]) || $this->is_admin == 1;
    }

    public function hasPermission($permission)
    {
        return $this->role && $this->role->permissions()
            ->where('permission', $permission)
            ->exists();
    }

    // ===== ORDERS =====
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrders()
    {
        return $this->orders()->whereIn('status', ['pending', 'processing', 'partial_paid']);
    }

    public function completedOrders()
    {
        return $this->orders()->where('status', 'completed');
    }

    // ===== WALLET =====
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function getWalletBalanceAttribute()
    {
        return $this->wallet?->balance ?? 0;
    }

    // ===== ADDRESSES =====
    public function deliveryAddresses()
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(DeliveryAddress::class)->where('is_default', true);
    }

    // ===== CARDS & BANK =====
    public function savedCards()
    {
        return $this->hasMany(SavedCard::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    // ===== VERIFICATIONS =====
    public function verifications()
    {
        return $this->hasMany(UserVerification::class);
    }

    // ===== REVIEWS =====
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ===== WISHLIST =====
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    // ===== NOTIFICATIONS =====
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('status', 'pending');
    }

    // ===== PAYMENT TRANSACTIONS =====
    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function walletWithdrawalRequests()
    {
        return $this->hasMany(WalletWithdrawalRequest::class);
    }

    public function installmentPayments()
    {
        return $this->hasManyThrough(InstallmentPayment::class, Order::class);
    }

    // ===== REQUESTS =====
    public function planChangeRequests()
    {
        return $this->hasMany(PlanChangeRequest::class);
    }

    public function accountDeletionRequests()
    {
        return $this->hasMany(AccountDeletionRequest::class);
    }

    public function productRequests()
    {
        return $this->hasMany(ProductRequest::class);
    }

    public function exchangeRequests()
    {
        return $this->hasMany(ExchangeRequest::class);
    }
}
