@extends('frontend.layouts.store')
@section('title', 'Withdraw to Bank — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-bank"></i> Withdraw</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Withdraw to bank</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Move your withdrawable balance to your bank account.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-xl px-4 sm:px-6">
        @if(session('error') || $errors->any())
        <div class="mb-6 rounded-xl border border-ember/30 bg-ember/5 p-4 text-sm font-semibold text-ember-deep" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') ?? $errors->first() }}
        </div>
        @endif

        <div class="os-card p-6 sm:p-8" x-data="withdrawPage({{ (float) ($withdrawableBalance ?? 0) }}, {{ (float) ($feePercent ?? 10) }})" x-reveal>
            <div class="flex items-center justify-between rounded-xl bg-paper-deep/60 p-4">
                <span class="text-sm text-slate">Withdrawable balance</span>
                <strong class="os-price text-lg text-grass-deep">{{ formatPrice($withdrawableBalance ?? 0, 2) }}</strong>
            </div>

            <form action="{{ route('wallet.withdraw.process') }}" method="POST" id="withdrawForm" class="mt-6">
                @csrf
                <div>
                    <label for="wdAmount" class="os-label"><i class="bi bi-cash-coin"></i> Amount (₦)</label>
                    <input type="number" name="amount" id="wdAmount" x-model="amount" class="os-input" min="100" step="100" placeholder="e.g. 5000" required>
                    @error('amount') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>

                <div class="mt-5">
                    <label for="wdBank" class="os-label"><i class="bi bi-bank"></i> Bank account</label>
                    @if(($banks ?? collect())->count() > 0)
                    <select id="wdBank" name="bank_account_id" class="os-input" required>
                        @foreach($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->bank_name }} •••• {{ substr($bank->account_number, -4) }} ({{ $bank->account_name }})</option>
                        @endforeach
                    </select>
                    @else
                    <div class="rounded-xl border border-dashed border-ink/15 bg-paper-deep/40 p-6 text-center text-sm text-slate">
                        No bank accounts yet. <a href="{{ route('profile.banks') }}" class="font-semibold text-brand underline underline-offset-2">Add one here</a>.
                    </div>
                    @endif
                    @error('bank_account_id') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 rounded-xl bg-paper-deep/60 p-4 text-sm">
                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-slate">Withdrawal amount</span>
                        <span class="os-price text-ink" x-text="money(fee ? amount - fee : 0)"></span>
                    </div>
                    <div class="flex items-center justify-between py-1.5">
                        <span class="text-slate">Processing fee ({{ $feePercent ?? 10 }}%)</span>
                        <span class="os-price text-ember-deep" x-text="money(fee)"></span>
                    </div>
                    <div class="mt-1.5 flex items-center justify-between border-t border-ink/10 pt-3">
                        <span class="font-semibold text-ink">You'll receive</span>
                        <span class="os-price text-lg font-bold text-brand" x-text="money(net)"></span>
                    </div>
                </div>

                <button type="submit" class="os-btn os-btn-mango mt-6 w-full py-3.5 text-base" {{ ($banks ?? collect())->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-send-fill"></i> Request withdrawal
                </button>
            </form>

            <p class="mt-5 flex items-start gap-2 text-xs leading-relaxed text-slate">
                <i class="bi bi-info-circle-fill mt-0.5 text-mango-ink"></i>
                A {{ $feePercent ?? 10 }}% processing fee applies to every withdrawal. Your request is reviewed by our team before funds are sent to your bank — you'll see its status in your wallet history.
            </p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function withdrawPage(balance, feePct) {
    return {
        amount: '',
        feePct,
        get num() { return parseFloat(this.amount) || 0; },
        get fee() { return Math.round(this.num * this.feePct) / 100; },
        get net() { return Math.max(0, this.num - this.fee); },
        money(v) { return '₦' + v.toFixed(2); },
    };
}
</script>
@endpush
