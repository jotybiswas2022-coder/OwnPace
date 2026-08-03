@extends('frontend.app')
@section('title', 'Change Plan — Order #'.$order->id)

@push('styles')
<style>
.fp-cp-hero {
    position: relative; padding: 34px 0 22px; overflow: hidden;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
    border-bottom: 1px solid var(--card-border);
}
.fp-cp-section { padding-bottom: 80px; min-height: 60vh; }
.fp-breadcrumb { display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-dim); }
.fp-breadcrumb a { color: var(--gold-400); }
.fp-breadcrumb i { font-size:11px; }

.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
.fp-alert.error { background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.25);color:#f87171; }
.fp-alert.info { background:rgba(59,130,246,0.08);border-color:rgba(59,130,246,0.25);color:#60a5fa; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-cp-card { background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius);overflow:hidden;transition:all 0.3s; }
.fp-cp-card:hover { border-color:rgba(234,179,8,0.15); }
.fp-cp-header { display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--card-border); }
.fp-cp-header h4 { font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px;margin:0; }
.fp-cp-header h4 i { color:var(--gold-500); }
.fp-cp-body { padding:20px; }
.fp-cp-body label { display:block;font-size:11px;color:var(--text-dim);font-weight:500;margin-bottom:2px;text-transform:uppercase;letter-spacing:0.5px; }
.fp-cp-body span { color:var(--text-primary);font-size:14px;font-weight:500;overflow-wrap:break-word; }
.fp-gold { color:var(--gold-400) !important;font-weight:700 !important; }

/* plan picker */
.fp-plan-option {
    position: relative; display:flex; align-items:center; gap:14px;
    background:var(--surface-dark); border:1.5px solid var(--card-border);
    border-radius:var(--radius-sm); padding:16px;
    cursor:pointer; transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-plan-option:hover { border-color:rgba(234,179,8,0.3); transform:translateY(-2px); }
.fp-plan-option input { position:absolute; opacity:0; pointer-events:none; }
.fp-plan-option.checked { border-color:var(--gold-500); background:rgba(234,179,8,0.05); box-shadow:var(--shadow-glow-sm); }
.fp-plan-radio {
    width:22px; height:22px; border-radius:50%; flex-shrink:0;
    border:2px solid var(--card-border); display:flex; align-items:center; justify-content:center;
    transition:all 0.3s;
}
.fp-plan-option.checked .fp-plan-radio { border-color:var(--gold-500); }
.fp-plan-option.checked .fp-plan-radio::after { content:''; width:10px; height:10px; border-radius:50%; background:var(--gold-500); }
.fp-plan-info { flex:1; min-width:0; }
.fp-plan-name { display:flex; align-items:center; gap:8px; color:var(--text-primary); font-size:15px; font-weight:600; }
.fp-plan-meta { color:var(--text-dim); font-size:12px; margin-top:2px; }
.fp-plan-desc { color:var(--text-muted); font-size:12px; margin-top:4px; line-height:1.5; }
.fp-plan-rate { font-family:'Syne',sans-serif; color:var(--gold-400); font-weight:700; font-size:13px; flex-shrink:0; }

.fp-input { width:100%;padding:12px 16px;background:var(--surface-dark);border:1.5px solid var(--card-border);border-radius:var(--radius-sm);color:var(--text-primary);font-size:14px;font-family:inherit;outline:none;transition:all 0.25s ease; }
.fp-input:focus { border-color:var(--gold-500);box-shadow:0 0 0 3px rgba(234,179,8,0.08); }
.fp-input::placeholder { color:var(--text-dim); }

.fp-submit-btn {
    display:inline-flex; align-items:center; gap:8px;
    background:linear-gradient(135deg,var(--gold-500),var(--gold-600));
    color:var(--near-black); padding:13px 28px; border-radius:var(--radius-sm);
    font-weight:700; font-size:14px; border:none; cursor:pointer;
    transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family:inherit;
}
.fp-submit-btn:hover { transform:translateY(-2px); box-shadow:var(--shadow-gold); }
.fp-cancel-link { display:inline-flex; align-items:center; gap:6px; color:var(--text-muted); font-size:13px; font-weight:600; text-decoration:none; padding:13px 18px; border-radius:var(--radius-sm); transition:all 0.2s; }
.fp-cancel-link:hover { color:var(--gold-400); background:rgba(234,179,8,0.06); }

@media (max-width: 768px) {
    .fp-cp-hero { padding: 24px 0 16px; }
}
</style>
@endpush

@section('content')
<section class="fp-cp-hero">
    <div class="container">
        <nav class="fp-breadcrumb reveal-up">
            <a href="{{ url('/') }}">Home</a><i class="bi bi-chevron-right"></i>
            <a href="{{ route('orders.index') }}">Orders</a><i class="bi bi-chevron-right"></i>
            <a href="{{ route('orders.show', $order) }}">Order #{{ $order->id }}</a><i class="bi bi-chevron-right"></i>
            <span>Change Plan</span>
        </nav>
    </div>
</section>

<section class="fp-cp-section">
    <div class="container">
        @if(session('error'))
        <div class="fp-alert error reveal-up"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-3">
                @include('frontend.partials.account-sidebar')
            </div>

            <div class="col-lg-9">
                @if(isset($pendingRequest) && $pendingRequest)
                <div class="fp-alert info reveal-up mb-4">
                    <i class="bi bi-hourglass-split"></i>
                    You already have a pending plan change request for this order. It's waiting for admin review.
                </div>
                @endif

                <div class="fp-cp-card reveal-left">
                    <div class="fp-cp-header">
                        <h4><i class="bi bi-receipt"></i> Request a New Payment Plan</h4>
                        <span class="fp-gold" style="font-size:13px;">Order #{{ $order->id }}</span>
                    </div>
                    <div class="fp-cp-body">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4"><label>Current Plan</label><span>{{ $order->installmentPlan?->name ?? '—' }}</span></div>
                            <div class="col-md-4"><label>Remaining Balance</label><span class="fp-gold">₦{{ number_format((float) $order->remaining_amount, 2) }}</span></div>
                            <div class="col-md-4"><label>Cadence</label><span>{{ $order->installmentPlan?->cadence ?? '—' }}</span></div>
                        </div>

                        <form method="POST" action="{{ route('orders.change.plan', $order) }}" id="planChangeForm">
                            @csrf
                            <label style="margin-bottom:8px;">Choose a new plan</label>
                            <div class="row g-3">
                                @forelse($plans as $plan)
                                <div class="col-md-6">
                                    <label class="fp-plan-option w-100" for="plan-{{ $plan->id }}">
                                        <input type="radio" name="requested_plan_id" id="plan-{{ $plan->id }}" value="{{ $plan->id }}" required {{ $loop->first ? 'checked' : '' }}>
                                        <span class="fp-plan-radio"></span>
                                        <span class="fp-plan-info">
                                            <span class="fp-plan-name">{{ $plan->name }}
                                                <small style="color:var(--text-dim);font-size:11px;font-weight:400;">{{ $plan->duration }} {{ $plan->type }}</small>
                                            </span>
                                            <span class="fp-plan-meta">{{ $plan->cadence }} · {{ $plan->duration }} installments</span>
                                            @if($plan->description)
                                            <span class="fp-plan-desc">{{ Str::limit($plan->description, 80) }}</span>
                                            @endif
                                        </span>
                                        <span class="fp-plan-rate">{{ $plan->interest_rate > 0 ? number_format((float) $plan->interest_rate, 1) . '%' : '0%' }}</span>
                                    </label>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="fp-alert info" style="margin-bottom:0;">
                                        <i class="bi bi-info-circle-fill"></i> No other plans are available right now.
                                    </div>
                                </div>
                                @endforelse
                            </div>

                            <label style="margin:18px 0 8px;">Why do you want to change your plan?</label>
                            <textarea name="reason" class="fp-input" rows="3" minlength="10" required
                                      placeholder="Tell us why you'd like a different duration (at least 10 characters)"></textarea>

                            <div class="d-flex align-items-center mt-4" style="gap:10px;">
                                <button type="submit" class="fp-submit-btn" {{ $plans->isEmpty() || (isset($pendingRequest) && $pendingRequest) ? 'disabled' : '' }}>
                                    <i class="bi bi-send-fill"></i> Submit Request
                                </button>
                                <a href="{{ route('orders.show', $order) }}" class="fp-cancel-link"><i class="bi bi-arrow-left"></i> Back to order</a>
                            </div>
                            <p class="mt-3" style="font-size:12px;color:var(--text-dim);">
                                <i class="bi bi-clock-history" style="color:var(--gold-500);"></i>
                                Your request stays pending until an admin approves or rejects it. Your current plan keeps working until then.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.fp-plan-option').forEach(opt => {
        const input = opt.querySelector('input');
        const sync = () => opt.classList.toggle('checked', input.checked);
        sync();
        opt.addEventListener('click', () => { input.checked = true; document.querySelectorAll('.fp-plan-option').forEach(o => o.classList.remove('checked')); opt.classList.add('checked'); });
    });
});
</script>
@endpush
