@extends('frontend.app')
@section('title', 'Order Confirmed — OwnPace Store')

@push('styles')
<style>
.fp-conf-section {
    position: relative; padding: 60px 0 80px; overflow: hidden;
    min-height: 80vh; display: flex; align-items: center;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
}
.fp-conf-orb {
    position: absolute; width: 500px; height: 500px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: confPulse 4s ease-in-out infinite;
}
.fp-conf-orb2 {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(34,197,94,0.03) 0%, transparent 60%);
    bottom: -100px; left: -80px; pointer-events: none;
    animation: confPulse 5s ease-in-out infinite reverse;
}
@keyframes confPulse { 0%,100%{transform:scale(1);opacity:0.5} 50%{transform:scale(1.1);opacity:1} }

.fp-confirm-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius-lg); padding: 56px 40px;
    animation: fadeUp 0.6s ease both;
    position: relative; overflow: hidden;
    contain: layout style;
}
.fp-confirm-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #4ade80, #22c55e, #16a34a);
}
@keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

.fp-confirm-icon {
    width: 90px; height: 90px; border-radius: 50%;
    background: rgba(34,197,94,0.08); border: 2px solid rgba(34,197,94,0.2);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px; font-size: 44px; color: #4ade80;
    animation: scaleIn 0.6s cubic-bezier(.34,1.56,.64,1) 0.2s both;
}
@keyframes scaleIn { from {transform:scale(0) rotate(-20deg);} to {transform:scale(1) rotate(0);} }

.fp-confirm-card h2 {
    font-family:'Syne',sans-serif; color: var(--text-primary);
    font-size: 30px; font-weight: 800; margin-bottom: 8px;
}
.fp-confirm-card > p { color: var(--text-muted); font-size: 15px; max-width: 400px; margin: 0 auto 28px; }

.fp-confirm-details {
    background: var(--surface-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius-sm); padding: 20px; margin-bottom: 28px;
    max-width: 360px; margin-left: auto; margin-right: auto;
    contain: layout style;
}
.fp-confirm-details div { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--card-border); }
.fp-confirm-details div:last-child { border-bottom: none; }
.fp-confirm-details div span { color: var(--text-dim); font-size: 14px; }
.fp-confirm-details div strong { color: var(--text-primary); font-size: 14px; }

.fp-confirm-actions { display: flex; flex-direction: column; align-items: center; gap: 10px; }
.fp-btn-ghost {
    display:inline-flex;align-items:center;gap:8px;padding:12px 28px;
    border-radius:var(--radius-sm);font-weight:600;font-size:14px;
    border:1.5px solid var(--card-border);color:var(--text-muted);
    transition:all 0.2s;text-decoration:none;
}
.fp-btn-ghost:hover { border-color:var(--gold-400);color:var(--gold-400); }

.fp-confetti {
    position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    pointer-events: none; overflow: hidden; user-select: none;
}
.fp-confetti span {
    position: absolute; width: 8px; height: 8px;
    background: var(--gold-500); border-radius: 2px;
    animation: confettiFall 6s ease-in-out infinite;
}
.fp-confetti span:nth-child(1) { left: 10%; animation-delay: 0s; background: #4ade80; }
.fp-confetti span:nth-child(2) { left: 30%; animation-delay: -1s; background: var(--gold-500); }
.fp-confetti span:nth-child(3) { left: 50%; animation-delay: -2s; background: #60a5fa; }
.fp-confetti span:nth-child(4) { left: 70%; animation-delay: -3s; background: #f472b6; }
.fp-confetti span:nth-child(5) { left: 90%; animation-delay: -4s; background: var(--gold-400); }
@keyframes confettiFall {
    0% { transform: translateY(-20px) rotate(0deg); opacity: 0; }
    10% { opacity: 1; }
    100% { transform: translateY(400px) rotate(720deg); opacity: 0; }
}
</style>
@endpush

@section('content')
<section class="fp-conf-section">
    <div class="fp-conf-orb" aria-hidden="true"></div>
    <div class="fp-conf-orb2" aria-hidden="true"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="fp-confirm-card text-center">
                    <div class="fp-confetti">
                        <span></span><span></span><span></span><span></span><span></span>
                    </div>
                    <div class="fp-confirm-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2>Order Confirmed!</h2>
                    <p>Thank you for your purchase. Your order has been placed successfully and we'll start processing it right away.</p>
                    <div class="fp-confirm-details">
                        <div><span>Order ID</span><strong>#{{ $order->id }}</strong></div>
                        <div><span>Total</span><strong style="color:var(--gold-400);">₦{{ number_format($order->total, 0) }}</strong></div>
                        <div><span>Status</span><strong style="color:#4ade80;">{{ ucfirst($order->status) }}</strong></div>
                    </div>
                    <div class="fp-confirm-actions">
                        <a href="{{ route('orders.show', $order) }}" class="btn-primary-gold" style="display:inline-flex;"><i class="bi bi-eye-fill"></i> View Order</a>
                        <a href="{{ url('/shop') }}" class="fp-btn-ghost"><i class="bi bi-grid-fill"></i> Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.partials.footer')
@endsection
