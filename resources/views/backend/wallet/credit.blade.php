@extends('backend.layouts.console')
@section('title', 'Credit Wallet — '.storeName().' Admin')
@section('page_title', 'Credit Wallet')

@section('content')

@if($errors->any())
<div class="mb-4 flex items-start gap-2 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill mt-0.5"></i> {{ $errors->first() }}
</div>
@endif

<div class="grid gap-6 lg:grid-cols-3">
    <!-- ===== CUSTOMER ===== -->
    <div class="os-card p-6 lg:col-span-1">
        <h2 class="font-display text-lg font-bold text-ink">Customer</h2>
        <div class="mt-4 flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-brand font-display text-sm font-bold text-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div>
                <p class="text-sm font-semibold text-ink">{{ $user->name }}</p>
                <p class="text-xs text-slate">{{ $user->email }}</p>
            </div>
        </div>
        <div class="mt-5 space-y-3 border-t border-ink/5 pt-4 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-slate">Wallet balance</span>
                <span class="font-mono font-semibold text-ink">₦{{ number_format($user->wallet?->balance ?? 0, 2) }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-slate">Withdrawable</span>
                <span class="font-mono font-semibold text-grass">
                    ₦{{ number_format($user->wallet ? \App\Services\WalletService::withdrawableBalance($user->wallet) : 0, 2) }}
                </span>
            </div>
        </div>
    </div>

    <!-- ===== CREDIT FORM ===== -->
    <div class="os-card p-6 lg:col-span-2">
        <h2 class="font-display text-lg font-bold text-ink">Manual credit</h2>
        <p class="mt-0.5 text-sm text-slate">Choose withdrawable or store-credit at the time of crediting.</p>

        <form action="{{ route('admin.wallet.credit', $user) }}" method="POST" class="mt-5 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="amount" class="mb-1.5 block text-xs font-semibold text-slate">Amount (₦)</label>
                    <input type="number" name="amount" id="amount" class="os-input w-full" step="0.01" min="0.01" placeholder="0.00" required>
                </div>
                <div>
                    <label for="type" class="mb-1.5 block text-xs font-semibold text-slate">Type</label>
                    <select name="type" id="type" class="os-input w-full">
                        <option value="cashback">Cashback</option>
                        <option value="reward">Reward</option>
                        <option value="store_credit">Store Credit</option>
                        <option value="adjustment">Adjustment</option>
                        <option value="bonus">Bonus</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="description" class="mb-1.5 block text-xs font-semibold text-slate">Description</label>
                <input type="text" name="description" id="description" class="os-input w-full" placeholder="e.g. Cashback on order #12345">
            </div>
            <div class="flex items-center gap-3 rounded-xl border border-ink/10 bg-paper p-4">
                <input type="checkbox" name="withdrawable" id="withdrawable" value="1" class="h-4 w-4 rounded accent-brand">
                <label for="withdrawable" class="cursor-pointer text-sm">
                    <span class="font-semibold text-ink">Withdrawable</span>
                    <span class="block text-xs text-slate">Customer can move this to their bank (10% fee applies)</span>
                </label>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Credit Wallet</button>
                <a href="{{ route('admin.wallet.index') }}" class="os-btn os-btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
