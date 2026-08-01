@extends('backend.layouts.console')
@section('title', 'Wallet Management — '.storeName().' Admin')
@section('page_title', 'Wallet Management')

@section('content')

@if(session('success'))
<div class="mb-4 flex items-start gap-2 rounded-xl border border-grass/25 bg-grass/10 p-4 text-sm text-grass">
    <i class="bi bi-check-circle-fill mt-0.5"></i> {{ session('success') }}
</div>
@endif

<div class="flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Customer wallets</h2>
        <p class="mt-0.5 text-sm text-slate">Search customers and credit their wallets with cashback, rewards or store credit.</p>
    </div>
    <a href="{{ route('admin.wallet.withdrawals') }}" class="os-btn os-btn-brand">
        <i class="bi bi-bank"></i> Withdrawal Requests
    </a>
</div>

<div class="mt-5 os-card p-4">
    <form method="GET" action="{{ route('admin.wallet.index') }}" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone…" class="os-input min-w-56 flex-1">
        <button type="submit" class="os-btn os-btn-ghost"><i class="bi bi-search"></i> Search</button>
    </form>
</div>

<div class="mt-5 overflow-x-auto">
    <table class="os-table w-full">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Balance</th>
                <th>Withdrawable</th>
                <th>Orders</th>
                <th class="w-40">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td data-label="Customer">
                    <p class="text-sm font-semibold text-ink">{{ $user->name }}</p>
                    <p class="text-xs text-slate">{{ $user->email ?? '—' }}</p>
                </td>
                <td data-label="Balance" class="font-mono text-sm text-ink">₦{{ number_format($user->wallet?->balance ?? 0, 2) }}</td>
                <td data-label="Withdrawable" class="font-mono text-sm text-grass">
                    ₦{{ number_format($user->wallet ? \App\Services\WalletService::withdrawableBalance($user->wallet) : 0, 2) }}
                </td>
                <td data-label="Orders">{{ $user->orders_count }}</td>
                <td data-label="Actions">
                    <a href="{{ route('admin.wallet.credit-form', $user) }}" class="os-btn os-btn-ghost os-btn-sm">
                        <i class="bi bi-plus-circle"></i> Credit
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-6 text-center text-sm text-slate">No customers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $users->withQueryString()->links() }}</div>

@endsection
