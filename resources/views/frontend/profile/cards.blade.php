@extends('frontend.app')
@section('title', 'My Cards — OwnPace Store')

@push('styles')
<style>
/* ===== CARDS HERO ===== */
.fp-cr-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-cr-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-cr-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: crPulse 6s ease-in-out infinite;
}
@keyframes crPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-cr-section { padding-bottom: 80px; min-height: 60vh; }
.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-card-item {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 24px;
    display: flex; align-items: center; gap: 14px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
}
.fp-card-item::after {
    content: ''; position: absolute; inset: 0; border-radius: var(--radius);
    pointer-events: none; opacity: 0;
    transition: opacity 0.4s;
    box-shadow: inset 0 0 0 1px rgba(234,179,8,0.15);
}
.fp-card-item:hover::after { opacity: 1; }
.fp-card-item:hover {
    border-color: rgba(234,179,8,0.25);
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(0,0,0,0.3);
}
.fp-card-type {
    width: 48px; height: 48px; border-radius: 10px;
    background: rgba(234,179,8,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-500); font-size: 22px; flex-shrink: 0;
}
.fp-card-details { flex: 1; }
.fp-card-details strong { display: block; color: var(--text-primary); font-size: 15px; }
.fp-card-details span { color: var(--text-dim); font-size: 12px; }
.fp-card-delete {
    color: var(--text-dim); font-size: 16px;
    padding: 8px; border-radius: 6px;
    transition: all 0.3s; text-decoration: none;
}
.fp-card-delete:hover { color: #ef4444; background: rgba(239,68,68,0.06); }

.fp-card-empty {
    text-align: center; padding: 60px 20px;
}
.fp-card-empty-icon {
    width: 72px; height: 72px; border-radius: 20px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px; font-size: 28px; color: var(--text-dim);
    transition: all 0.3s;
}
.fp-card-empty:hover .fp-card-empty-icon {
    border-color: rgba(234,179,8,0.2); transform: scale(1.05);
}
.fp-card-empty p { color: var(--text-muted); font-size: 15px; margin: 0; }

@media (max-width: 768px) {
    .fp-cr-hero { padding: 36px 0 20px; }
}
</style>
@endpush

@section('content')
<section class="fp-cr-hero">
    <div class="fp-cr-hero-grid" aria-hidden="true"></div>
    <div class="fp-cr-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-credit-card-fill"></i> Payment Cards</div>
            <h2>Saved Cards</h2>
            <p>Cards saved from your previous payments</p>
        </div>
    </div>
</section>

<section class="fp-cr-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @forelse($cards ?? [] as $card)
            <div class="col-lg-4 col-md-6">
                <div class="fp-card-item reveal-up">
                    <div class="fp-card-type"><i class="bi bi-credit-card-2-front-fill"></i></div>
                    <div class="fp-card-details">
                        <strong>•••• {{ $card->last_four }}</strong>
                        <span>Expires {{ $card->expiry_month }}/{{ $card->expiry_year }}</span>
                    </div>
                    <a href="{{ route('profile.cards.delete', $card) }}" class="fp-card-delete" onclick="return confirm('Remove this card?')" aria-label="Remove card ending in {{ $card->last_four }}">
                        <i class="bi bi-trash-fill"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="fp-card-empty reveal-up">
                    <div class="fp-card-empty-icon"><i class="bi bi-credit-card"></i></div>
                    <p>No saved cards yet. Cards are saved automatically after your first payment.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@include('frontend.partials.footer')
@endsection
