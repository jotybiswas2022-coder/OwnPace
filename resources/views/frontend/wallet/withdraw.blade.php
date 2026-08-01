@extends('frontend.app')
@section('title', 'Withdraw to Bank — OwnPace Store')

@push('styles')
<style>
.fp-wd-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-wd-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: wdPulse 6s ease-in-out infinite;
}
@keyframes wdPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }
.fp-wd-section { padding-bottom: 80px; min-height: 60vh; }

.fp-alert {
    display:flex;align-items:center;gap:10px;
    background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);
    color:#f87171;padding:14px 20px;border-radius:var(--radius-sm);
    font-weight:500;font-size:13px;margin-bottom:24px;
}

.fp-wd-card {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    padding: 32px; max-width: 520px; margin: 0 auto;
    transition: all 0.3s;
}
.fp-wd-card:hover { border-color: rgba(234,179,8,0.18); box-shadow: var(--shadow-glow-sm); }
.fp-wd-avail {
    display: flex; align-items: center; justify-content: space-between;
    background: var(--surface-dark); border-radius: var(--radius-sm);
    padding: 16px 18px; margin-bottom: 20px;
}
.fp-wd-avail span { color: var(--text-dim); font-size: 13px; }
.fp-wd-avail strong {
    font-family: 'IBM Plex Mono', 'JetBrains Mono', monospace;
    font-variant-numeric: tabular-nums;
    color: #4ade80; font-size: 18px;
}
.fp-input {
    width: 100%; padding: 12px 16px;
    background: var(--surface-dark);
    border: 1.5px solid var(--card-border);
    border-radius: var(--radius-sm);
    color: var(--text-primary); font-size: 14px;
    font-family: inherit; outline: none;
    transition: all 0.25s ease;
}
.fp-input:focus { border-color: var(--gold-500); box-shadow: 0 0 0 3px rgba(234,179,8,0.08); }
.fp-input option { background: var(--card-dark); color: var(--text-primary); }

.fp-wd-breakdown {
    background: var(--surface-dark); border-radius: var(--radius-sm);
    padding: 16px 18px; margin-top: 16px;
}
.fp-wd-breakdown .row {
    display: flex; justify-content: space-between;
    font-size: 13px; color: var(--text-muted); padding: 6px 0;
}
.fp-wd-breakdown .row span:last-child {
    font-family: 'IBM Plex Mono', 'JetBrains Mono', monospace;
    font-variant-numeric: tabular-nums; color: var(--text-primary);
}
.fp-wd-breakdown .row.fee span:last-child { color: #f87171; }
.fp-wd-breakdown .row.net { border-top: 1px solid var(--card-border); margin-top: 6px; padding-top: 10px; font-weight: 600; }
.fp-wd-breakdown .row.net span:last-child { color: var(--gold-400); font-size: 16px; }

.fp-wd-submit {
    width: 100%; margin-top: 20px; padding: 14px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); border: none; border-radius: var(--radius-sm);
    font-weight: 700; font-size: 15px; font-family: 'Syne', sans-serif;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all 0.3s;
}
.fp-wd-submit:hover { transform: translateY(-2px); box-shadow: var(--shadow-gold); }
.fp-wd-note {
    display: flex; align-items: flex-start; gap: 10px;
    margin-top: 16px; padding: 14px 18px;
    background: rgba(234,179,8,0.05); border: 1px solid rgba(234,179,8,0.15);
    border-radius: var(--radius-sm); font-size: 12px; color: var(--text-dim); line-height: 1.6;
}
.fp-wd-note i { color: var(--gold-500); font-size: 15px; flex-shrink: 0; margin-top: 1px; }
.fp-no-banks { text-align: center; color: var(--text-dim); font-size: 13px; padding: 12px; }
</style>
@endpush

@section('content')
<section class="fp-wd-hero">
    <div class="fp-wd-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-bank"></i> Withdraw</div>
            <h2>Withdraw to Bank</h2>
            <p>Move your withdrawable balance to your bank account</p>
        </div>
    </div>
</section>

<section class="fp-wd-section">
    <div class="container">
        @if(session('error'))
        <div class="fp-alert reveal-up"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="fp-alert reveal-up"><i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}</div>
        @endif

        <div class="fp-wd-card reveal-up">
            <div class="fp-wd-avail">
                <span>Withdrawable balance</span>
                <strong>₦{{ number_format($withdrawableBalance ?? 0, 2) }}</strong>
            </div>

            <form action="{{ route('wallet.withdraw.process') }}" method="POST" id="withdrawForm">
                @csrf
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-cash-coin" style="color:var(--gold-500);"></i> Amount (₦)
                    </label>
                    <input type="number" name="amount" id="wdAmount" class="fp-input" min="100" step="100" placeholder="e.g. 5000" required>
                </div>

                <div>
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">
                        <i class="bi bi-bank" style="color:var(--gold-500);"></i> Bank Account
                    </label>
                    @if(($banks ?? collect())->count() > 0)
                    <select name="bank_account_id" class="fp-input" required>
                        @foreach($banks as $bank)
                        <option value="{{ $bank->id }}">
                            {{ $bank->bank_name }} •••• {{ substr($bank->account_number, -4) }} ({{ $bank->account_name }})
                        </option>
                        @endforeach
                    </select>
                    @else
                    <div class="fp-no-banks">
                        No bank accounts yet.
                        <a href="{{ route('profile.banks') }}" style="color:var(--gold-400);">Add one here</a>.
                    </div>
                    @endif
                </div>

                <div class="fp-wd-breakdown">
                    <div class="row"><span>Withdrawal amount</span><span id="brAmount">₦0.00</span></div>
                    <div class="row fee"><span>Processing fee ({{ $feePercent ?? 10 }}%)</span><span id="brFee">₦0.00</span></div>
                    <div class="row net"><span>You'll receive</span><span id="brNet">₦0.00</span></div>
                </div>

                <button type="submit" class="fp-wd-submit">
                    <i class="bi bi-send-fill"></i> Request Withdrawal
                </button>
            </form>

            <div class="fp-wd-note">
                <i class="bi bi-info-circle-fill"></i>
                A {{ $feePercent ?? 10 }}% processing fee applies to every withdrawal.
                Your request is reviewed by our team before funds are sent to your bank — you'll see its status in your wallet history.
            </div>
        </div>
    </div>
</section>
@include('frontend.partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amount = document.getElementById('wdAmount');
    const feePct = {{ $feePercent ?? 10 }};

    function updateBreakdown() {
        const val = parseFloat(amount.value) || 0;
        const fee = Math.round(val * feePct) / 100;
        const net = val - fee;
        document.getElementById('brAmount').textContent = '₦' + val.toFixed(2);
        document.getElementById('brFee').textContent = '₦' + fee.toFixed(2);
        document.getElementById('brNet').textContent = '₦' + net.toFixed(2);
    }

    amount.addEventListener('input', updateBreakdown);
    updateBreakdown();
});
</script>
@endsection
