@extends('frontend.layouts.store')
@section('title', 'My Wallet — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-wallet2"></i> My wallet</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Wallet dashboard</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Your balance, withdrawable funds, and transaction history.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Balance column --}}
            <div class="space-y-5" x-reveal>
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-mango via-mango-deep to-mango-deep p-8 text-center shadow-lift">
                    <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-black/10" aria-hidden="true"></div>
                    <span class="relative mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-black/15 text-2xl text-ink"><i class="bi bi-wallet2"></i></span>
                    <p class="relative mt-4 text-xs font-bold uppercase tracking-[0.14em] text-ink/70">Total balance</p>
                    <p class="relative mt-2 font-mono text-4xl font-bold tracking-tight text-ink">{{ formatPrice($wallet->balance ?? 0, 2) }}</p>
                    <p class="relative mt-2 text-xs font-medium text-ink/60">Available for purchases &amp; installments</p>
                    <a href="{{ route('wallet.fund') }}" class="os-btn relative mt-6 px-7" style="background:var(--ink);color:var(--mango);"><i class="bi bi-plus-circle-fill"></i> Fund wallet</a>
                </div>

                <div class="os-card p-5">
                    <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-layers-half text-mango-deep"></i> Your balance</h2>
                    <p class="mt-1 text-xs text-slate">What you can spend vs. what can move to your bank.</p>
                    <dl class="mt-4 space-y-0 divide-y divide-ink/5">
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-arrow-down-circle-fill text-grass-deep"></i>
                                <div>
                                    <p class="text-sm font-medium text-ink">Withdrawable</p>
                                    <p class="text-[11px] text-slate">Can move to your bank (fee applies)</p>
                                </div>
                            </div>
                            <dd class="os-price text-grass-deep">{{ formatPrice($withdrawableBalance ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-lock-fill text-mango-ink"></i>
                                <div>
                                    <p class="text-sm font-medium text-ink">Store credit</p>
                                    <p class="text-[11px] text-slate">Spendable on purchases only</p>
                                </div>
                            </div>
                            <dd class="os-price text-mango-ink">{{ formatPrice($spendableBalance ?? 0, 2) }}</dd>
                        </div>
                    </dl>
                    @if(($withdrawableBalance ?? 0) > 0)
                    <a href="{{ route('wallet.withdraw') }}" class="os-btn os-btn-ghost w-full"><i class="bi bi-bank"></i> Withdraw to bank</a>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="os-card os-card-hover p-5">
                        <i class="bi bi-arrow-down-circle-fill text-xl text-grass-deep"></i>
                        <p class="mt-3 font-mono text-sm font-semibold text-ink">{{ formatPrice($wallet->total_deposited ?? 0, 2) }}</p>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Total deposited</p>
                    </div>
                    <div class="os-card os-card-hover p-5">
                        <i class="bi bi-arrow-up-circle-fill text-xl text-ember-deep"></i>
                        <p class="mt-3 font-mono text-sm font-semibold text-ink">{{ formatPrice($wallet->total_withdrawn ?? 0, 2) }}</p>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Total withdrawn</p>
                    </div>
                </div>
            </div>

            {{-- Transactions --}}
            <div class="os-card overflow-hidden lg:col-span-2 lg:self-start" x-reveal="120">
                <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
                    <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-clock-history text-mango-deep"></i> Recent transactions</h2>
                    <a href="{{ route('wallet.history') }}" class="text-xs font-semibold text-brand transition-colors hover:text-brand-deep">View all</a>
                </div>
                <div class="divide-y divide-ink/5">
                    @forelse($transactions ?? [] as $txn)
                    @php $isDebit = in_array($txn->type, ['payment', 'withdrawal']); @endphp
                    <div class="flex items-center gap-4 px-5 py-4 transition-colors hover:bg-paper-deep/40">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-lg {{ $isDebit ? 'bg-ember/10 text-ember-deep' : 'bg-grass/10 text-grass-deep' }}">
                            <i class="bi {{ $isDebit ? 'bi-arrow-up-circle-fill' : 'bi-arrow-down-circle-fill' }}"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-ink">{{ $txn->description ?? ucfirst($txn->type) }}</p>
                            <p class="text-xs text-slate">{{ $txn->created_at->format('M d, Y h:i A') }}</p>
                            <span class="os-chip mt-1.5 px-2 py-0.5 text-[10px] {{ $txn->withdrawable ? 'os-chip-grass' : 'os-chip-mango' }}">
                                {{ $txn->withdrawable ? 'Withdrawable' : 'Store credit' }}
                            </span>
                        </div>
                        <span class="os-price shrink-0 {{ $isDebit ? 'text-ember-deep' : 'text-grass-deep' }}">
                            {{ $isDebit ? '-' : '+' }}{{ formatPrice($txn->amount, 2) }}
                        </span>
                    </div>
                    @empty
                    <div class="px-5 py-14 text-center">
                        <span class="os-empty-icon mx-auto"><i class="bi bi-clock-history"></i></span>
                        <p class="mt-4 text-sm text-slate">No transactions yet — fund your wallet or make a purchase to get started.</p>
                        <a href="{{ route('wallet.fund') }}" class="os-btn os-btn-brand os-btn-sm mt-5"><i class="bi bi-plus-circle-fill"></i> Fund wallet</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
