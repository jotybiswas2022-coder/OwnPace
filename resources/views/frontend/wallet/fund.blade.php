@extends('frontend.app')
@section('title', 'Fund Wallet — OwnPace Store')

@push('styles')
<style>
/* ===== FUND HERO ===== */
.fp-fw-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-fw-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-fw-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: fwPulse 6s ease-in-out infinite;
}
@keyframes fwPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-fw-section { padding-bottom: 80px; min-height: 60vh; }

/* ===== FUND CARD ===== */
.fp-fund-card {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    padding: 36px; max-width: 520px; margin: 0 auto;
    transition: all 0.3s ease;
}
.fp-fund-card:hover {
    border-color: rgba(234,179,8,0.18);
    box-shadow: var(--shadow-glow-sm);
}
.fp-fund-balance {
    text-align: center; padding: 24px 20px;
    background: var(--surface-dark);
    border-radius: var(--radius-sm);
    position: relative; overflow: hidden;
}
.fp-fund-balance::before {
    content: ''; position: absolute; top: -30px; right: -30px;
    width: 100px; height: 100px; border-radius: 50%;
    background: rgba(234,179,8,0.04);
}
.fp-fund-balance span {
    display: block; color: var(--text-dim);
    font-size: 13px; margin-bottom: 6px;
}
.fp-fund-balance strong {
    font-family: 'Syne', sans-serif;
    font-size: 34px; font-weight: 800;
    background: linear-gradient(135deg, var(--gold-400), var(--gold-600));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
}

.fp-form-group label {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; font-weight: 600;
    color: var(--text-primary); margin-bottom: 8px;
}
.fp-form-group label i { color: var(--gold-500); }

.fp-amount-presets {
    display: flex; gap: 8px; flex-wrap: wrap;
}
.fp-preset {
    padding: 9px 18px; border-radius: 8px;
    background: var(--surface-dark);
    border: 1px solid var(--card-border);
    color: var(--text-muted); font-size: 13px; font-weight: 600;
    cursor: pointer; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family: inherit;
    touch-action: manipulation;
}
.fp-preset:hover, .fp-preset.active {
    background: rgba(234,179,8,0.1);
    border-color: rgba(234,179,8,0.3);
    color: var(--gold-400);
    transform: translateY(-1px);
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
.fp-input:focus {
    border-color: var(--gold-500);
    box-shadow: 0 0 0 3px rgba(234,179,8,0.08);
}
.fp-input option { background: var(--card-dark); color: var(--text-primary); }

.fp-fund-submit {
    width: 100%; padding: 15px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); border: none;
    border-radius: var(--radius-sm);
    font-weight: 700; font-size: 15px;
    font-family: 'Syne', sans-serif; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative; overflow: hidden;
}
.fp-fund-submit::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.15) 50%, transparent 80%);
    transform: translateX(-100%); transition: transform 0.5s;
}
.fp-fund-submit:hover::before { transform: translateX(100%); }
.fp-fund-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(234,179,8,0.3);
}
.fp-fund-note {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 18px;
    background: rgba(234,179,8,0.05);
    border: 1px solid rgba(234,179,8,0.15);
    border-radius: var(--radius-sm);
    font-size: 12px; color: var(--text-dim);
    line-height: 1.5;
}
.fp-fund-note i {
    color: var(--gold-500); font-size: 15px;
    flex-shrink: 0;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .fp-fw-hero { padding: 36px 0 20px; }
    .fp-fund-card { padding: 24px 20px; }
    .fp-amount-presets { gap: 6px; }
    .fp-preset { padding: 8px 14px; font-size: 12px; flex: 1; text-align: center; }
}
</style>
@endpush

@section('content')
<section class="fp-fw-hero">
    <div class="fp-fw-hero-grid" aria-hidden="true"></div>
    <div class="fp-fw-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-plus-circle-fill"></i> Fund Wallet</div>
            <h2>Add Funds to Your Wallet</h2>
            <p>Top up your wallet for seamless purchases</p>
        </div>
    </div>
</section>

<section class="fp-fw-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="fp-fund-card reveal-up">
                    <div class="fp-fund-balance">
                        <span>Current Balance</span>
                        <strong>₦{{ number_format(auth()->user()->wallet?->balance ?? 0, 0) }}</strong>
                    </div>

                    <form action="{{ route('wallet.fund.process') }}" method="POST" class="mt-4">
                        @csrf
                        <div class="fp-form-group">
                            <label><i class="bi bi-cash-coin"></i> Amount to Add (₦)</label>
                            <div class="fp-amount-presets">
                                <button type="button" class="fp-preset" data-amount="1000">₦1,000</button>
                                <button type="button" class="fp-preset" data-amount="5000">₦5,000</button>
                                <button type="button" class="fp-preset" data-amount="10000">₦10,000</button>
                                <button type="button" class="fp-preset" data-amount="20000">₦20,000</button>
                                <button type="button" class="fp-preset" data-amount="50000">₦50,000</button>
                            </div>
                            <input type="number" name="amount" id="fundAmount" class="fp-input mt-2"
                                   placeholder="Enter custom amount" min="100" step="100" required>
                        </div>

                        <div class="fp-form-group mt-3">
                            <label><i class="bi bi-credit-card-fill"></i> Payment Method</label>
                            <select name="gateway" class="fp-input" required>
                                <option value="paystack">Paystack (Card, Bank, USSD)</option>
                                <option value="flutterwave">Flutterwave (Card, Bank, Mobile Money)</option>
                                <option value="korapay">Korapay (Card, Bank Transfer, USSD)</option>
                            </select>
                        </div>

                        <button type="submit" class="fp-fund-submit mt-4">
                            <i class="bi bi-wallet2"></i> Fund Wallet
                        </button>
                    </form>

                    <div class="fp-fund-note mt-3">
                        <i class="bi bi-info-circle-fill"></i>
                        Funds are non-withdrawable and can only be used for purchases on OwnPace Store.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.partials.footer')

<script>
document.querySelectorAll('.fp-preset').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.fp-preset').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('fundAmount').value = this.dataset.amount;
    });
});
</script>
@endsection
