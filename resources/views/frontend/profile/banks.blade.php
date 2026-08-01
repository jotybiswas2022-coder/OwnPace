@extends('frontend.app')
@section('title', 'My Bank Accounts — OwnPace Store')

@push('styles')
<style>
/* ===== BANKS HERO ===== */
.fp-bk-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-bk-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-bk-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: bkPulse 6s ease-in-out infinite;
}
@keyframes bkPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-bk-section { padding-bottom: 80px; min-height: 60vh; }
.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-bank-card {
    background: var(--card-dark);border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 24px;
    position: relative; height: 100%;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-bank-card::after {
    content: ''; position: absolute; inset: 0; border-radius: var(--radius);
    pointer-events: none; opacity: 0;
    transition: opacity 0.4s;
    box-shadow: inset 0 0 0 1px rgba(234,179,8,0.15);
}
.fp-bank-card:hover::after { opacity: 1; }
.fp-bank-card:hover {
    border-color: rgba(234,179,8,0.25);
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(0,0,0,0.3);
}
.fp-bank-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: rgba(234,179,8,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-500); font-size: 20px; margin-bottom: 12px;
}
.fp-bank-card h5 { color: var(--text-primary); font-size: 16px; font-weight: 600; }
.fp-bank-account {
    font-family: 'Syne', sans-serif;
    color: var(--gold-400); font-size: 18px;
    font-weight: 700; margin: 4px 0;
    letter-spacing: 1px;
}
.fp-bank-name { color: var(--text-dim); font-size: 13px; }
.fp-bank-delete {
    position: absolute; top: 12px; right: 12px;
    color: var(--text-dim); padding: 6px;
    border-radius: 6px; transition: all 0.3s;
    text-decoration: none;
}
.fp-bank-delete:hover { color: #ef4444; background: rgba(239,68,68,0.06); }

.fp-bank-empty {
    text-align: center; padding: 60px 20px;
}
.fp-bank-empty-icon {
    width: 72px; height: 72px; border-radius: 20px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px; font-size: 28px; color: var(--text-dim);
    transition: all 0.3s;
}
.fp-bank-empty:hover .fp-bank-empty-icon {
    border-color: rgba(234,179,8,0.2); transform: scale(1.05);
}
.fp-bank-empty p { color: var(--text-muted); font-size: 15px; margin: 0; }

.fp-input { width:100%;padding:12px 16px;background:var(--surface-dark);border:1.5px solid var(--card-border);border-radius:var(--radius-sm);color:var(--text-primary);font-size:14px;font-family:inherit;outline:none;transition:all 0.25s ease; }
.fp-input:focus { border-color:var(--gold-500);box-shadow:0 0 0 3px rgba(234,179,8,0.08); }
.fp-input::placeholder { color:var(--text-dim); }

.fp-modal .modal-content { background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius-lg); }
.fp-modal .modal-header { border-bottom-color:var(--card-border);padding:20px 24px; }
.fp-modal .modal-title { color:var(--text-primary);font-family:'Syne',sans-serif;font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px; }
.fp-modal .modal-title i { color:var(--gold-500); }
.fp-modal .modal-body { padding:24px; }
.fp-modal .modal-footer { border-top-color:var(--card-border);padding:16px 24px; }

@media (max-width: 768px) {
    .fp-bk-hero { padding: 36px 0 20px; }
}
</style>
@endpush

@section('content')
<section class="fp-bk-hero">
    <div class="fp-bk-hero-grid" aria-hidden="true"></div>
    <div class="fp-bk-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up" style="text-align:left;">
            <div class="section-badge" style="display:inline-flex;"><i class="bi bi-bank"></i> Bank Accounts</div>
            <h2>My Bank Accounts</h2>
            <p>Manage your saved bank accounts for withdrawals</p>
        </div>
    </div>
</section>

<section class="fp-bk-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-4 reveal-up">
            <a href="#" class="btn-primary-gold" data-bs-toggle="modal" data-bs-target="#addBankModal"><i class="bi bi-plus-lg"></i> Add Bank</a>
        </div>

        <div class="row g-4">
            @forelse($banks ?? [] as $bank)
            <div class="col-lg-4 col-md-6">
                <div class="fp-bank-card reveal-up">
                    <div class="fp-bank-icon"><i class="bi bi-bank2"></i></div>
                    <h5>{{ $bank->bank_name }}</h5>
                    <p class="fp-bank-account">{{ $bank->account_number }}</p>
                    <span class="fp-bank-name">{{ $bank->account_name }}</span>
                    <a href="{{ route('profile.banks.delete', $bank) }}" class="fp-bank-delete" onclick="return confirm('Remove this bank account?')" aria-label="Remove bank account {{ $bank->account_number }}">
                        <i class="bi bi-trash-fill"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="fp-bank-empty reveal-up">
                    <div class="fp-bank-empty-icon"><i class="bi bi-bank"></i></div>
                    <p>No bank accounts added yet.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<div class="modal fade" id="addBankModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fp-modal">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-bank"></i> Add Bank Account</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.banks.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12"><input type="text" name="bank_name" class="fp-input" placeholder="Bank Name" required></div>
                        <div class="col-12"><input type="text" name="account_name" class="fp-input" placeholder="Account Name" required></div>
                        <div class="col-12"><input type="text" name="account_number" class="fp-input" placeholder="Account Number" required></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary-gold w-100 justify-content-center">Save Bank Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('frontend.partials.footer')
@endsection
