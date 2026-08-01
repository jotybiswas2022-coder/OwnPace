@extends('frontend.app')
@section('title', 'Wallet History — OwnPace Store')

@push('styles')
<style>
/* ===== HISTORY HERO ===== */
.fp-wh-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-wh-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-wh-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: whPulse 6s ease-in-out infinite;
}
@keyframes whPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-wh-section { padding-bottom: 80px; min-height: 60vh; }

/* ===== TABLE ===== */
.fp-txn-table-wrap {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: all 0.3s;
}
.fp-txn-table-wrap:hover {
    border-color: rgba(234,179,8,0.15);
    box-shadow: var(--shadow-glow-sm);
}
.fp-txn-table { width: 100%; border-collapse: collapse; }
.fp-txn-table th {
    padding: 14px 24px; text-align: left;
    font-size: 11px; font-weight: 600;
    color: var(--text-dim); text-transform: uppercase;
    letter-spacing: 0.8px;
    border-bottom: 1px solid var(--card-border);
    background: var(--surface-dark);
}
.fp-txn-table td {
    padding: 14px 24px;
    border-bottom: 1px solid var(--card-border);
    font-size: 13px;
    transition: background 0.2s;
}
.fp-txn-table tr:last-child td { border-bottom: none; }
.fp-txn-table tr:hover td { background: rgba(234,179,8,0.02); }

.fp-txn-type {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 12px; border-radius: 6px;
    font-size: 11px; font-weight: 600;
}
.fp-txn-type i { font-size: 10px; }
.fp-txn-type.credit { background: rgba(34,197,94,0.12); color: #4ade80; }
.fp-txn-type.debit { background: rgba(239,68,68,0.12); color: #f87171; }

.fp-txn-val {
    font-weight: 700; font-size: 15px;
    font-family: 'Syne', sans-serif;
}
.fp-txn-val.credit { color: #4ade80; }
.fp-txn-val.debit { color: #f87171; }

.fp-wh-empty {
    text-align: center; padding: 80px 20px;
}
.fp-wh-empty-icon {
    width: 80px; height: 80px; border-radius: 20px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px; font-size: 32px; color: var(--text-dim);
    transition: all 0.3s;
}
.fp-wh-empty:hover .fp-wh-empty-icon {
    border-color: rgba(234,179,8,0.2);
    transform: scale(1.05);
}
.fp-wh-empty p { color: var(--text-muted); font-size: 15px; margin: 0; }

/* ===== PAGINATION ===== */
.fp-pagination-custom { margin-top: 32px; }
.fp-pagination-custom nav { display: flex; justify-content: center; }
.fp-pagination-custom .pagination {
    display: flex; gap: 4px; list-style: none; padding: 0; margin: 0;
}
.fp-pagination-custom .page-item .page-link {
    display: flex; align-items: center; justify-content: center;
    min-width: 40px; height: 40px;
    padding: 8px 14px;
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: 10px !important;
    color: var(--text-muted);
    font-size: 14px; font-weight: 600;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none;
    margin: 0 !important;
}
.fp-pagination-custom .page-item .page-link:hover {
    background: rgba(234,179,8,0.08);
    border-color: rgba(234,179,8,0.2);
    color: var(--gold-400);
    transform: translateY(-2px);
}
.fp-pagination-custom .page-item.active .page-link {
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    border-color: transparent;
    color: var(--near-black);
    box-shadow: var(--shadow-gold);
}
.fp-pagination-custom .page-item.active .page-link:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-gold-lg);
    color: var(--near-black);
}
.fp-pagination-custom .page-item.disabled .page-link {
    opacity: 0.4; cursor: not-allowed;
}
.fp-pagination-custom .page-item.disabled .page-link:hover {
    transform: none;
    background: var(--card-dark);
    border-color: var(--card-border);
    color: var(--text-muted);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .fp-wh-hero { padding: 36px 0 20px; }
    .fp-txn-table th,
    .fp-txn-table td { padding: 10px 14px; font-size: 12px; }
    .fp-txn-val { font-size: 13px; }
    .fp-pagination-custom .page-item .page-link {
        min-width: 36px; height: 36px;
        padding: 6px 10px; font-size: 13px;
    }
}
</style>
@endpush

@section('content')
<section class="fp-wh-hero">
    <div class="fp-wh-hero-grid" aria-hidden="true"></div>
    <div class="fp-wh-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up" style="text-align:left;">
            <div class="section-badge" style="display:inline-flex;"><i class="bi bi-clock-history"></i> Transaction History</div>
            <h2>Wallet History</h2>
            <p>View all your wallet transactions</p>
        </div>
    </div>
</section>

<section class="fp-wh-section">
    <div class="container">
        <div class="d-flex justify-content-end mb-4 reveal-up">
            <a href="{{ route('wallet.index') }}" class="btn-primary-gold"><i class="bi bi-wallet2"></i> Wallet</a>
        </div>

        @if(isset($transactions) && $transactions->count() > 0)
        <div class="fp-txn-table-wrap reveal-up">
            <table class="fp-txn-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-calendar3"></i> Date</th>
                        <th><i class="bi bi-card-text"></i> Description</th>
                        <th><i class="bi bi-tag"></i> Type</th>
                        <th class="text-end"><i class="bi bi-cash"></i> Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $txn)
                    <tr>
                        <td style="color:var(--text-dim);font-size:12px;">{{ $txn->created_at->format('M d, Y') }}</td>
                        <td style="color:var(--text-primary);font-weight:500;">{{ $txn->description ?? ucfirst($txn->type) }}</td>
                        <td>
                            <span class="fp-txn-type {{ $txn->type }}">{{ ucfirst($txn->type) }}</span>
                        </td>
                        <td class="text-end fp-txn-val {{ $txn->type }}">
                            {{ $txn->type == 'credit' ? '+' : '-' }}₦{{ number_format($txn->amount, 0) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="fp-wh-empty reveal-up">
            <div class="fp-wh-empty-icon"><i class="bi bi-clock-history"></i></div>
            <p>No transaction history yet.</p>
        </div>
        @endif
    </div>
</section>
@include('frontend.partials.footer')
@endsection
