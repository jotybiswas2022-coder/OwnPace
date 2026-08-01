@extends('frontend.app')
@section('title', 'My Wallet — OwnPace Store')

@push('styles')
<style>
/* ===== WALLET HERO ===== */
.fp-wa-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-wa-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-wa-orb {
    position: absolute; width: 450px; height: 450px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -180px; right: -100px; pointer-events: none;
    animation: waPulse 6s ease-in-out infinite;
}
@keyframes waPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-wa-section { padding-bottom: 80px; min-height: 60vh; }

.fp-alert {
    display:flex;align-items:center;gap:10px;
    background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);
    color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);
    font-weight:500;font-size:13px;margin-bottom:24px;
    animation: alertSlide 0.4s ease-out;
}
@keyframes alertSlide {
    from { opacity:0; transform: translateY(-10px); }
    to { opacity:1; transform: translateY(0); }
}

/* ===== BALANCE CARD ===== */
.fp-wallet-balance-card {
    background: linear-gradient(135deg, #eab308, #ca8a04, #a16207);
    border-radius: var(--radius-lg);
    padding: 36px 28px; text-align: center;
    position: relative; overflow: hidden;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-wallet-balance-card:hover { transform: translateY(-4px); }
.fp-wallet-balance-card::before {
    content:''; position:absolute; top:-50px; right:-50px;
    width: 200px; height: 200px; border-radius: 50%;
    background: rgba(0,0,0,0.06);
    animation: balanceOrb 8s ease-in-out infinite alternate;
}
.fp-wallet-balance-card::after {
    content:''; position:absolute; bottom:-80px; left:10%;
    width: 260px; height: 260px; border-radius: 50%;
    background: rgba(0,0,0,0.04);
    animation: balanceOrb 10s ease-in-out infinite alternate-reverse;
}
@keyframes balanceOrb {
    0% { transform: translate(0,0) scale(1); }
    100% { transform: translate(20px,15px) scale(1.15); }
}
.fp-wb-icon {
    width: 60px; height: 60px; border-radius: 16px;
    background: rgba(0,0,0,0.12);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px; font-size: 28px;
    color: rgba(0,0,0,0.6); position: relative; z-index: 1;
    backdrop-filter: blur(4px);
}
.fp-wallet-balance-card h4 {
    font-family: 'Syne', sans-serif;
    color: rgba(0,0,0,0.7); font-size: 15px;
    margin-bottom: 8px; position: relative; z-index: 1;
    letter-spacing: 0.5px; text-transform: uppercase;
}
.fp-wb-amount {
    font-family: 'Syne', sans-serif;
    font-size: 44px; font-weight: 800;
    color: var(--near-black); margin-bottom: 8px;
    position: relative; z-index: 1;
    letter-spacing: -1px;
}
.fp-wallet-balance-card p {
    color: rgba(0,0,0,0.5); font-size: 13px;
    margin-bottom: 24px; position: relative; z-index: 1;
}
.fp-fund-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--near-black); color: var(--gold-400);
    padding: 13px 30px; border-radius: 10px;
    font-weight: 700; font-size: 14px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative; z-index: 1;
    text-decoration: none;
}
.fp-fund-btn:hover {
    transform: translateY(-2px); color: var(--gold-300);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}
.fp-fund-btn i { font-size: 16px; }

/* ===== STATS ===== */
.fp-wallet-stats { display: flex; flex-direction: column; gap: 8px; }
.fp-ws-item {
    display: flex; align-items: center; gap: 14px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius-sm); padding: 16px 18px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none;
}
.fp-ws-item:hover {
    border-color: rgba(234,179,8,0.25);
    transform: translateY(-2px);
    box-shadow: var(--shadow-glow-sm);
}
.fp-ws-item i { font-size: 26px; flex-shrink: 0; }
.fp-ws-item strong {
    display: block; color: var(--text-primary);
    font-size: 15px; font-family: 'Syne', sans-serif;
}
.fp-ws-item span { color: var(--text-dim); font-size: 12px; }

/* ===== TRANSACTIONS ===== */
.fp-wallet-transactions {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: all 0.3s;
}
.fp-wallet-transactions:hover {
    border-color: rgba(234,179,8,0.15);
    box-shadow: var(--shadow-glow-sm);
}
.fp-wt-header {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 18px 24px;
    border-bottom: 1px solid var(--card-border);
    background: var(--surface-dark);
}
.fp-wt-header h4 {
    font-family: 'Syne', sans-serif;
    font-size: 15px; font-weight: 700;
    color: var(--text-primary);
    display: flex; align-items: center; gap: 8px; margin: 0;
}
.fp-wt-header h4 i { color: var(--gold-500); font-size: 16px; }
.fp-wt-header .fp-view-all {
    color: var(--gold-400); font-size: 12px;
    font-weight: 600; text-decoration: none;
    padding: 6px 14px; border-radius: 6px;
    transition: all 0.3s;
}
.fp-wt-header .fp-view-all:hover {
    background: rgba(234,179,8,0.08);
    color: var(--gold-300);
}
.fp-txn-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 24px;
    border-bottom: 1px solid var(--card-border);
    transition: background 0.2s;
}
.fp-txn-item:last-child { border-bottom: none; }
.fp-txn-item:hover { background: rgba(234,179,8,0.02); }
.fp-txn-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.fp-txn-icon.credit { background: rgba(34,197,94,0.1); color: #4ade80; }
.fp-txn-icon.debit { background: rgba(239,68,68,0.1); color: #f87171; }
.fp-txn-info { flex: 1; min-width: 0; }
.fp-txn-info strong {
    display: block; color: var(--text-primary);
    font-size: 13px; font-weight: 500;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.fp-txn-info small { color: var(--text-dim); font-size: 11px; }
.fp-txn-amount {
    font-weight: 700; font-size: 15px;
    font-family: 'Syne', sans-serif;
    white-space: nowrap;
}
.fp-txn-amount.credit { color: #4ade80; }
.fp-txn-amount.debit { color: #f87171; }

.fp-txn-empty {
    text-align: center; padding: 40px 20px;
}
.fp-txn-empty-icon {
    width: 60px; height: 60px; border-radius: 50%;
    background: var(--surface-dark);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 12px; font-size: 24px; color: var(--text-dim);
}
.fp-txn-empty p { color: var(--text-dim); font-size: 13px; margin: 0; }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .fp-wa-hero { padding: 36px 0 20px; }
    .fp-wb-amount { font-size: 36px; }
    .fp-wallet-balance-card { padding: 28px 20px; }
}
</style>
@endpush

@section('content')
<section class="fp-wa-hero">
    <div class="fp-wa-hero-grid" aria-hidden="true"></div>
    <div class="fp-wa-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-wallet2"></i> My Wallet</div>
            <h2>Wallet Dashboard</h2>
            <p>Manage your funds and view transactions</p>
        </div>
    </div>
</section>

<section class="fp-wa-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="fp-wallet-balance-card reveal-left">
                    <div class="fp-wb-icon"><i class="bi bi-wallet2"></i></div>
                    <h4>Your Balance</h4>
                    <div class="fp-wb-amount">₦{{ number_format($wallet->balance ?? 0, 0) }}</div>
                    <p>Available for purchases &amp; installments</p>
                    <a href="{{ route('wallet.fund') }}" class="fp-fund-btn"><i class="bi bi-plus-circle-fill"></i> Fund Wallet</a>
                </div>

                <div class="fp-wallet-stats mt-3">
                    <div class="fp-ws-item reveal-left" style="transition-delay:0.1s;">
                        <i class="bi bi-arrow-down-circle-fill" style="color:#4ade80;"></i>
                        <div>
                            <strong>₦{{ number_format($wallet->total_credited ?? 0, 0) }}</strong>
                            <span>Total Credited</span>
                        </div>
                    </div>
                    <div class="fp-ws-item reveal-left" style="transition-delay:0.2s;">
                        <i class="bi bi-arrow-up-circle-fill" style="color:#ef4444;"></i>
                        <div>
                            <strong>₦{{ number_format($wallet->total_debited ?? 0, 0) }}</strong>
                            <span>Total Debited</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="fp-wallet-transactions reveal-right">
                    <div class="fp-wt-header">
                        <h4><i class="bi bi-clock-history"></i> Recent Transactions</h4>
                        <a href="{{ route('wallet.history') }}" class="fp-view-all">View All</a>
                    </div>
                    <div class="fp-wt-body">
                        @forelse($transactions ?? [] as $txn)
                        <div class="fp-txn-item">
                            <div class="fp-txn-icon {{ $txn->type }}">
                                <i class="bi {{ $txn->type == 'credit' ? 'bi-arrow-down-circle-fill' : 'bi-arrow-up-circle-fill' }}"></i>
                            </div>
                            <div class="fp-txn-info">
                                <strong>{{ $txn->description ?? ucfirst($txn->type) }}</strong>
                                <small>{{ $txn->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <div class="fp-txn-amount {{ $txn->type }}">
                                {{ $txn->type == 'credit' ? '+' : '-' }}₦{{ number_format($txn->amount, 0) }}
                            </div>
                        </div>
                        @empty
                        <div class="fp-txn-empty">
                            <div class="fp-txn-empty-icon"><i class="bi bi-clock-history"></i></div>
                            <p>No transactions yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')
@endsection
