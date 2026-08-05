@extends('frontend.layouts.store')
@section('title', 'Wallet History — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-end justify-between gap-4 px-4 sm:px-6">
        <div>
            <span class="os-eyebrow"><i class="bi bi-clock-history"></i> Transaction history</span>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Wallet history</h1>
            <p class="mt-2 text-sm text-slate">View all your wallet transactions.</p>
        </div>
        <a href="{{ route('wallet.index') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-wallet2"></i> Wallet</a>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-5xl px-4 sm:px-6">
        @if(isset($transactions) && $transactions->count() > 0)
        <div class="os-card overflow-hidden" x-reveal>
            <div class="overflow-x-auto">
                <table class="os-table w-full">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $txn)
                        @php $isDebit = in_array($txn->type, ['payment', 'withdrawal']); @endphp
                        <tr>
                            <td data-label="Date" class="text-sm text-slate">{{ $txn->created_at->format('M d, Y') }}</td>
                            <td data-label="Description" class="text-sm font-medium text-ink">{{ $txn->description ?? ucfirst($txn->type) }}</td>
                            <td data-label="Type">
                                <span class="os-chip {{ $isDebit ? 'os-chip-ember' : 'os-chip-grass' }}">{{ ucfirst($txn->type) }}</span>
                            </td>
                            <td data-label="Amount" class="text-right">
                                <span class="os-price {{ $isDebit ? 'text-ember-deep' : 'text-grass-deep' }}">{{ $isDebit ? '-' : '+' }}{{ formatPrice($txn->amount, 0) }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="mx-auto max-w-lg" x-reveal>
            <x-frontend.partials.empty-state
                icon="bi-clock-history"
                title="No transactions yet"
                message="Your wallet activity — top-ups, refunds and payments — will show up here."
                actionLabel="Fund wallet"
                actionUrl="{{ route('wallet.fund') }}"
            />
        </div>
        @endif
    </div>
</section>

@endsection
