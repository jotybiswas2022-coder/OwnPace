@extends('frontend.layouts.store')
@section('title', 'Fund Wallet — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-plus-circle-fill"></i> Fund wallet</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Add funds to your wallet</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Top up your wallet for seamless purchases.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-xl px-4 sm:px-6">
        <div class="os-card p-6 sm:p-8" x-data="fundWallet()" x-reveal>
            <div class="rounded-xl bg-paper-deep/60 p-6 text-center">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate">Current balance</p>
                <p class="mt-2 font-mono text-3xl font-bold text-ink">{{ formatPrice(auth()->user()->wallet?->balance ?? 0, 0) }}</p>
            </div>

            @if($errors->any())
            <div class="mt-5 rounded-xl border border-ember/30 bg-ember/5 p-4 text-sm font-semibold text-ember-deep" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('wallet.fund.process') }}" method="POST" class="mt-6">
                @csrf
                <div>
                    <label for="fundAmount" class="os-label"><i class="bi bi-cash-coin"></i> Amount to add (₦)</label>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach([1000, 5000, 10000, 20000, 50000] as $osPreset)
                        <button type="button" class="os-chip border border-ink/10 bg-paper-deep/60 text-ink transition-all hover:border-mango/50"
                                :class="amount == {{ $osPreset }} ? 'bg-mango text-ink border-transparent' : ''"
                                @click="setAmount({{ $osPreset }})">
                            {{ formatPrice($osPreset, 0) }}
                        </button>
                        @endforeach
                    </div>
                    <input type="number" name="amount" id="fundAmount" x-model="amount" class="os-input mt-3" placeholder="Enter custom amount" min="100" step="100" required>
                    @error('amount') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label for="os-fund-gateway" class="os-label"><i class="bi bi-credit-card-fill"></i> Payment method</label>
                    <select id="os-fund-gateway" name="gateway" class="os-input" required>
                        <option value="paystack">Paystack (Card, Bank, USSD)</option>
                        <option value="flutterwave">Flutterwave (Card, Bank, Mobile Money)</option>
                        <option value="korapay">Korapay (Card, Bank Transfer, USSD)</option>
                    </select>
                    @error('gateway') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="os-btn os-btn-mango mt-6 w-full py-3.5 text-base"><i class="bi bi-wallet2"></i> Fund wallet</button>
            </form>

            <div class="mt-5 flex items-start gap-3 rounded-xl border border-mango/30 bg-mango/5 p-4 text-xs leading-relaxed text-slate">
                <i class="bi bi-info-circle-fill mt-0.5 text-mango-ink"></i>
                <p>
                    @php
                        $topUpWithdrawable = \App\Services\WalletService::topUpWithdrawalAllowed();
                        $bonusPct = (float) (\App\Models\Setting::first()?->topup_bonus_percent ?? 0);
                    @endphp
                    @if($bonusPct > 0)
                        You'll receive an extra <strong class="text-ink">{{ $bonusPct }}% bonus store credit</strong> on this top-up.
                    @endif
                    @if($topUpWithdrawable)
                        Top-ups are withdrawable to your bank ({{ \App\Services\WalletService::withdrawalFeePercent() }}% fee applies).
                    @else
                        Top-up funds are store credit — spendable on purchases, not withdrawable to a bank.
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function fundWallet() {
    return {
        amount: '',
        setAmount(val) {
            this.amount = val;
        },
    };
}
</script>
@endpush
