@extends('frontend.app')
@section('title', 'Order #'.$order->id.' — OwnPace Store')

@push('styles')
<style>
/* ---- Progress Ring (design-system component, themed for this page) ---- */
:root {
    --mango: #EAB308;
    --mango-deep: #CA8A04;
    --grass: #4ade80;
    --indigo: #F4F4F5;
    --slate: #71717A;
    --font-mono: 'IBM Plex Mono', ui-monospace, monospace;
}
.progress-ring {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    line-height: 1;
}
.progress-ring svg { transform: rotate(-90deg); display: block; }
.progress-ring .pr-track { fill: none; stroke: rgba(255,255,255,0.08); }
.progress-ring .pr-bar {
    fill: none;
    stroke-linecap: round;
    stroke: var(--mango);
    transition: stroke-dashoffset 1s cubic-bezier(0.22, 1, 0.36, 1);
    will-change: stroke-dashoffset;
}
.progress-ring .pr-center {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    text-align: center;
}
.progress-ring .pr-value {
    font-family: var(--font-mono);
    font-variant-numeric: tabular-nums;
    font-weight: 600;
    color: var(--indigo);
    line-height: 1.1;
}
.progress-ring .pr-label {
    font-size: 10px; font-weight: 600;
    letter-spacing: 0.08em; text-transform: uppercase;
    color: var(--slate);
}
.progress-ring.pr-done .pr-bar { stroke: var(--grass); }

.fp-ord-hero {
    position: relative; padding: 30px 0 20px; overflow: hidden;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
}
.fp-ord-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: ordPulse 4s ease-in-out infinite;
}
@keyframes ordPulse { 0%,100%{transform:scale(1);opacity:0.5} 50%{transform:scale(1.1);opacity:1} }

.fp-ord-section { padding-bottom: 80px; min-height: 60vh; }
.fp-breadcrumb { display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-dim); }
.fp-breadcrumb a { color: var(--gold-400); }
.fp-breadcrumb i { font-size:11px; }

.fp-detail-card {
    background:var(--card-dark);border:1px solid var(--card-border);
    border-radius:var(--radius);overflow:hidden;
    transition: all 0.3s; contain:layout style;
}
.fp-detail-card:hover { border-color: rgba(234,179,8,0.15); }
.fp-dc-header {
    display:flex;align-items:center;justify-content:space-between;
    padding:16px 20px;border-bottom:1px solid var(--card-border);
}
.fp-dc-header h4 {
    font-family:'Syne',sans-serif;font-size:15px;font-weight:700;
    color:var(--text-primary);display:flex;align-items:center;gap:8px;margin:0;
}
.fp-dc-header h4 i { color:var(--gold-500); }
.fp-dc-body { padding:20px; }
.fp-dc-body label { display:block;font-size:11px;color:var(--text-dim);font-weight:500;margin-bottom:2px;text-transform:uppercase;letter-spacing:0.5px; }
.fp-dc-body span { color:var(--text-primary);font-size:14px;font-weight:500;overflow-wrap:break-word; }
.fp-gold { color:var(--gold-400) !important;font-weight:700 !important; }
.fp-green { color:#4ade80 !important;font-weight:700 !important; }
.fp-mono { font-family:'IBM Plex Mono',ui-monospace,monospace; font-variant-numeric:tabular-nums; letter-spacing:-0.2px; }

.fp-order-status { padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;text-transform:uppercase; }
.fp-order-status.processing,.fp-order-status.partial_paid { background:rgba(234,179,8,0.15);color:var(--gold-400); }
.fp-order-status.completed { background:rgba(34,197,94,0.15);color:#4ade80; }
.fp-order-status.cancelled { background:rgba(239,68,68,0.15);color:#ef4444; }
.fp-order-status.shipped { background:rgba(59,130,246,0.15);color:#60a5fa; }
.fp-order-status.pending { background:rgba(148,163,184,0.15);color:#94a3b8; }

.fp-oi-row { display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--card-border); }
.fp-oi-row:last-child { border-bottom:none; }
.fp-oi-row img { width:52px;height:52px;border-radius:6px;object-fit:cover;background:var(--dark-900); }
.fp-oi-no-img-sm { width:52px;height:52px;border-radius:6px;background:var(--dark-900);display:flex;align-items:center;justify-content:center;color:var(--card-border); }
.fp-oi-detail { flex:1; }
.fp-oi-name { display:block;color:var(--text-primary);font-size:13px;font-weight:500;overflow-wrap:break-word; }
.fp-oi-meta { font-size:11px;color:var(--text-dim); }
.fp-oi-total { font-weight:600;color:var(--gold-400);font-size:14px; }

.fp-action-btn {
    padding:12px;border-radius:var(--radius-sm);font-size:13px;font-weight:600;cursor:pointer;
    display:flex;align-items:center;justify-content:center;gap:8px;
    transition:all 0.2s;font-family:inherit;border:1px solid var(--card-border);
    background:var(--surface-dark);color:var(--text-muted);text-decoration:none;
}
.fp-action-btn:hover { border-color:var(--gold-400);color:var(--gold-400); }
.fp-action-btn.gold { background:linear-gradient(135deg,var(--gold-500),var(--gold-600));color:var(--near-black);border-color:var(--gold-500); }
.fp-action-btn.gold:hover { box-shadow:0 4px 16px rgba(234,179,8,0.3); }
.fp-action-btn.outline { border-color:rgba(234,179,8,0.3);color:var(--gold-400);background:rgba(234,179,8,0.05); }
.fp-action-btn.danger { border-color:rgba(239,68,68,0.3);color:#ef4444;background:rgba(239,68,68,0.05); }
.fp-action-btn.wallet { border-color:rgba(74,222,128,0.35);color:#4ade80;background:rgba(74,222,128,0.05); }

/* ---- Schedule table ---- */
.fp-sched { width:100%; border-collapse:collapse; }
.fp-sched th {
    padding:12px 20px; text-align:left;
    font-size:11px; font-weight:700; color:var(--text-dim);
    text-transform:uppercase; letter-spacing:0.5px;
    border-bottom:1px solid var(--card-border); background:var(--surface-dark);
}
.fp-sched td { padding:14px 20px; border-bottom:1px solid var(--card-border); vertical-align:middle; }
.fp-sched tr:last-child td { border-bottom:none; }
.fp-sched .fp-is-num { font-weight:700; color:var(--text-primary); font-size:13px; }
.fp-sched .fp-is-date { font-size:12px; color:var(--text-dim); }
.fp-sched .fp-is-amount { font-weight:600; color:var(--gold-400); font-size:14px; }
.fp-sched .fp-is-paid .fp-is-num, .fp-sched .fp-is-paid .fp-is-date { color:var(--text-dim); }
.fp-sched .fp-is-paid .fp-is-amount { color:#4ade80; }
.fp-sched .fp-is-current { background:rgba(234,179,8,0.04); }

.fp-is-chip {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600;
}
.fp-is-chip.paid { background:rgba(34,197,94,0.14); color:#4ade80; }
.fp-is-chip.upcoming { background:rgba(148,163,184,0.12); color:#94a3b8; }
.fp-is-chip.overdue { background:rgba(239,68,68,0.14); color:#f87171; }
.fp-is-chip.late { background:rgba(239,68,68,0.1); color:#f87171; border:1px dashed rgba(239,68,68,0.35); }

.fp-ring-wrap {
    display:flex; flex-direction:column; align-items:center;
    padding:32px 20px 24px; text-align:center;
}
.fp-ring-copy { color:var(--text-muted); font-size:14px; max-width:340px; margin-top:18px; line-height:1.6; }
.fp-shippable-badge {
    display: flex; align-items: center; gap: 12px;
    margin-top: 16px; padding: 12px 18px;
    background: rgba(74,222,128,0.08);
    border: 1px solid rgba(74,222,128,0.3);
    border-radius: var(--radius-sm); text-align: left;
    max-width: 360px;
}
.fp-shippable-badge i { font-size: 22px; color: #4ade80; flex-shrink: 0; }
.fp-shippable-badge strong { display: block; color: #4ade80; font-size: 13px; }
.fp-shippable-badge small { color: var(--text-dim); font-size: 11px; }
.fp-star-input .bi-star-fill { color: var(--gold-500); text-shadow: 0 0 12px rgba(234,179,8,0.4); }

.fp-ring-stats {
    display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin-top:20px;
}
.fp-ring-stat {
    padding:10px 16px; background:var(--surface-dark);
    border:1px solid var(--card-border); border-radius:var(--radius-sm); min-width:120px;
}
.fp-ring-stat strong { display:block; color:var(--text-primary); font-size:15px; }
.fp-ring-stat small { color:var(--text-dim); font-size:11px; text-transform:uppercase; letter-spacing:0.5px; }

.fp-partial-form { display:flex; gap:8px; }
.fp-partial-form input {
    flex:1; background:var(--surface-dark); border:1.5px solid var(--card-border);
    color:var(--text-primary); padding:10px 12px; border-radius:var(--radius-sm);
    font-family:'IBM Plex Mono',monospace; font-size:14px; outline:none; min-width:0;
}
.fp-partial-form input:focus { border-color:var(--gold-500); }

.fp-tracking-item { display:flex; align-items:flex-start; gap:12px; margin-bottom:14px; }
.fp-tracking-dot { width:12px;height:12px;border-radius:50%;margin-top:4px;flex-shrink:0; }
.fp-tracking-dot.completed { background:#4ade80;box-shadow:0 0 0 4px rgba(34,197,94,0.15); }
.fp-tracking-dot.pending { background:var(--card-border); }
.fp-tracking-dot.shipped { background:#60a5fa;box-shadow:0 0 0 4px rgba(59,130,246,0.15); }

@media (max-width: 768px) {
    .fp-sched thead { display:none; }
    .fp-sched tbody, .fp-sched tr, .fp-sched td { display:block; }
    .fp-sched tr { padding:4px 0; border-bottom:1px solid var(--card-border); }
    .fp-sched td {
        display:flex; justify-content:space-between; align-items:center;
        padding:8px 16px; border-bottom:none;
    }
    .fp-sched td:before {
        content:attr(data-label);
        font-weight:600; color:var(--text-dim);
        font-size:10px; text-transform:uppercase; letter-spacing:0.5px;
    }
}
</style>
@endpush

@section('content')
<section class="fp-ord-hero">
    <div class="fp-ord-orb" aria-hidden="true"></div>
    <div class="container">
        <nav class="fp-breadcrumb reveal-up">
            <a href="{{ url('/') }}">Home</a><i class="bi bi-chevron-right"></i>
            <a href="{{ route('orders.index') }}">Orders</a><i class="bi bi-chevron-right"></i>
            <span>Order #{{ $order->id }}</span>
        </nav>
    </div>
</section>

<section class="fp-ord-section">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-8">
                {{-- ===== PAYMENT PROGRESS — the ring ===== --}}
                <div class="fp-detail-card reveal-left">
                    <div class="fp-dc-header">
                        <h4><i class="bi bi-graph-up-arrow"></i> Your journey to ownership</h4>
                        <span class="fp-order-status {{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                    </div>
                    <div class="fp-ring-wrap">
                        <x-progress-ring
                            :percentage="$progressPct"
                            :amount="'₦' . number_format((float) $order->remaining_amount, 0)"
                            :label="$progressPct >= 100 ? 'paid off' : 'left to own'"
                            :size="220"
                            :stroke="14"
                        />
                        @if($order->isEligibleForShipping() && $order->delivery_status !== 'delivered')
                        <div class="fp-shippable-badge">
                            <i class="bi bi-truck-front-fill"></i>
                            <div>
                                <strong>Your item can be shipped here</strong>
                                <small>You've reached {{ \App\Services\DeliveryStatusService::thresholdPercent() }}% paid — delivery is unlocked.</small>
                            </div>
                        </div>
                        @endif
                        <p class="fp-ring-copy">{{ $progressLabel }}</p>
                        <div class="fp-ring-stats">
                            <div class="fp-ring-stat">
                                <strong class="fp-mono">₦{{ number_format((float) $order->paid_amount, 0) }}</strong>
                                <small>Paid</small>
                            </div>
                            <div class="fp-ring-stat">
                                <strong class="fp-mono">₦{{ number_format((float) $order->remaining_amount, 0) }}</strong>
                                <small>Remaining</small>
                            </div>
                            @if($order->installmentPlan)
                            <div class="fp-ring-stat">
                                <strong>{{ $order->installmentPlan->name }}</strong>
                                <small>{{ $order->installmentPlan->cadence }}</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ===== INSTALLMENT SCHEDULE — reassuring, not a spreadsheet ===== --}}
                @if($order->installmentPlan && $order->installmentPayments->count() > 0)
                <div class="fp-detail-card mt-4 reveal-left" style="transition-delay:0.05s;">
                    <div class="fp-dc-header">
                        <h4><i class="bi bi-calendar-check"></i> Payment schedule</h4>
                        @php
                            $paidCount = $order->installmentPayments->where('status', 'paid')->count();
                            $totalCount = $order->installmentPayments->count();
                        @endphp
                        <span style="font-size:12px;color:var(--text-muted);">{{ $paidCount }} of {{ $totalCount }} paid</span>
                    </div>
                    <div class="fp-dc-body p-0">
                        <table class="fp-sched">
                            <thead>
                                <tr>
                                    <th>Installment</th>
                                    <th>Due date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->installmentPayments->sortBy('installment_number') as $ip)
                                @php
                                    $isNext = $nextPayment && $nextPayment->id === $ip->id;
                                    $lateFee = $ip->is_overdue ? \App\Services\InstallmentScheduleService::lateFeeFor($ip) : 0;
                                @endphp
                                <tr class="{{ $ip->status === 'paid' ? 'fp-is-paid' : ($isNext ? 'fp-is-current' : '') }}">
                                    <td data-label="Installment">
                                        <span class="fp-is-num">Installment #{{ $ip->installment_number }}</span>
                                    </td>
                                    <td data-label="Due date">
                                        <span class="fp-is-date">{{ $ip->due_date->format('M d, Y') }}</span>
                                        @if($ip->is_overdue)
                                            <span class="fp-is-chip late" style="margin-left:6px;"><i class="bi bi-exclamation-triangle-fill"></i> Late</span>
                                        @endif
                                    </td>
                                    <td data-label="Amount">
                                        <span class="fp-is-amount fp-mono">₦{{ number_format((float) $ip->amount, 2) }}</span>
                                        @if($ip->late_fee > 0)
                                            <small style="display:block;color:#f87171;font-size:11px;">incl. ₦{{ number_format((float) $ip->late_fee, 2) }} late fee</small>
                                        @elseif($lateFee > 0)
                                            <small style="display:block;color:#f87171;font-size:11px;">+ ₦{{ number_format($lateFee, 2) }} late fee due</small>
                                        @endif
                                    </td>
                                    <td data-label="Status">
                                        @if($ip->status === 'paid')
                                            <span class="fp-is-chip paid"><i class="bi bi-check-circle-fill"></i> Paid</span>
                                        @elseif($ip->is_overdue)
                                            <span class="fp-is-chip overdue"><i class="bi bi-clock-fill"></i> Overdue</span>
                                        @else
                                            <span class="fp-is-chip upcoming">Upcoming</span>
                                        @endif
                                    </td>
                                    <td data-label="">
                                        @if($isNext)
                                            <form action="{{ route('orders.pay.installment', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="installment_payment_id" value="{{ $ip->id }}">
                                                <button type="submit" class="fp-action-btn gold" style="padding:8px 16px;font-size:12px;">
                                                    <i class="bi bi-coin"></i> Pay now
                                                </button>
                                            </form>
                                        @elseif($ip->status !== 'paid')
                                            <span style="font-size:11px;color:var(--text-dim);">Due later</span>
                                        @else
                                            <span style="color:#4ade80;font-size:13px;"><i class="bi bi-check2"></i></span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- ===== ORDER DETAILS ===== --}}
                <div class="fp-detail-card mt-4 reveal-left" style="transition-delay:0.1s;">
                    <div class="fp-dc-header"><h4><i class="bi bi-receipt"></i> Order details</h4></div>
                    <div class="fp-dc-body">
                        <div class="row g-3">
                            <div class="col-md-4"><label>Order Date</label><span>{{ $order->created_at->format('M d, Y') }}</span></div>
                            <div class="col-md-4"><label>Total Amount</label><span class="fp-gold">₦{{ number_format((float) $order->grand_total, 2) }}</span></div>
                            <div class="col-md-4"><label>Paid Amount</label><span class="fp-green">₦{{ number_format((float) $order->paid_amount, 2) }}</span></div>
                            <div class="col-md-4"><label>Remaining</label><span class="fp-mono">₦{{ number_format((float) $order->remaining_amount, 2) }}</span></div>
                            <div class="col-md-4"><label>Payment Plan</label><span>{{ $order->installmentPlan?->name ?? ($order->payment_type === 'full' ? 'Paid in full' : '—') }}</span></div>
                            <div class="col-md-4"><label>Delivery Status</label><span>{{ ucfirst(str_replace('_', ' ', $order->delivery_status ?? 'pending')) }}</span></div>
                            @if($order->deliveryProxyUser)
                            <div class="col-md-4"><label>Delivery Proxy</label><span>{{ $order->deliveryProxyUser->name }} <small style="display:block;color:var(--text-dim);font-size:11px;">{{ $order->deliveryProxyUser->phone ?? $order->deliveryProxyUser->email }}</small></span></div>
                            @endif
                        </div>
                        @if($order->installmentPlan && $order->interest_amount > 0)
                        <div style="margin-top:14px;padding:12px 16px;background:var(--surface-dark);border-radius:var(--radius-sm);font-size:12px;color:var(--text-dim);">
                            <i class="bi bi-info-circle-fill" style="color:var(--gold-500);"></i>
                            Includes ₦{{ number_format((float) $order->interest_amount, 2) }} interest
                            @if($order->has_insurance) · ₦{{ number_format((float) $order->insurance_fee, 2) }} insurance @endif
                            @if((float) $order->shipping_fee > 0) · ₦{{ number_format((float) $order->shipping_fee, 2) }} shipping @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ===== DELIVERY TRACKING TIMELINE ===== --}}
                @if($order->deliveryTrackings && $order->deliveryTrackings->count() > 0)
                <div class="fp-detail-card mt-4 reveal-left" style="transition-delay:0.12s;">
                    <div class="fp-dc-header">
                        <h4><i class="bi bi-truck-front-fill"></i> Delivery tracking</h4>
                        @if($order->delivery_status === 'delivered')
                            <span class="fp-order-status completed">Delivered</span>
                        @elseif($order->delivery_status === 'eligible')
                            <span class="fp-order-status" style="background:rgba(74,222,128,0.15);color:#4ade80;">Eligible</span>
                        @else
                            <span class="fp-order-status {{ $order->delivery_status ?? 'pending' }}">{{ ucfirst(str_replace('_', ' ', $order->delivery_status ?? 'pending')) }}</span>
                        @endif
                    </div>
                    <div style="padding:24px 20px;">
                        @php
                            $steps = [
                                ['key' => 'processing', 'icon' => 'bi-gear-fill', 'label' => 'Processing', 'desc' => 'Your order is being prepared'],
                                ['key' => 'shipped', 'icon' => 'bi-truck-front-fill', 'label' => 'Shipped', 'desc' => 'On its way to you'],
                                ['key' => 'delivered', 'icon' => 'bi-check-circle-fill', 'label' => 'Delivered', 'desc' => 'Handed over to you'],
                            ];
                            $current = $order->delivery_status ?? 'pending';
                            $reached = ['processing' => $current !== 'pending', 'shipped' => in_array($current, ['shipped','in_transit','out_for_delivery','delivered','failed']), 'delivered' => in_array($current, ['delivered']) || ($current === 'failed')];
                        @endphp
                        <div style="display:flex;align-items:flex-start;gap:0;">
                            @foreach($steps as $i => $st)
                            <div style="flex:1;text-align:center;position:relative;">
                                @if($i < count($steps) - 1)
                                <div style="position:absolute;top:18px;left:calc(50% + 22px);right:calc(-50% + 22px);height:2px;background:{{ $reached[$steps[$i+1]['key']] ? '#4ade80' : 'var(--card-border)' }};"></div>
                                @endif
                                <div style="width:38px;height:38px;border-radius:50%;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;font-size:15px;background:{{ $reached[$st['key']] ? 'linear-gradient(135deg,var(--gold-500),var(--gold-600))' : 'var(--surface-dark)' }};color:{{ $reached[$st['key']] ? 'var(--near-black)' : 'var(--text-dim)' }};border:1px solid {{ $reached[$st['key']] ? 'transparent' : 'var(--card-border)' }};box-shadow:{{ ($current === $st['key']) ? '0 0 0 6px rgba(234,179,8,0.12)' : 'none' }};">
                                    <i class="bi {{ $st['icon'] }}"></i>
                                </div>
                                <strong style="display:block;font-size:12px;color:var(--text-primary);">{{ $st['label'] }}</strong>
                                <small style="font-size:10px;color:var(--text-dim);">{{ $reached[$st['key']] ? $st['desc'] : 'Pending' }}</small>
                            </div>
                            @endforeach
                        </div>

                        <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--card-border);">
                            @foreach($order->deliveryTrackings->sortByDesc('tracked_at') as $dt)
                            <div class="fp-tracking-item">
                                <div class="fp-tracking-dot {{ $dt->status === 'delivered' || $dt->status === 'eligible' ? 'completed' : ($dt->status === 'shipped' ? 'shipped' : 'pending') }}"></div>
                                <div>
                                    <strong style="color:var(--text-primary);font-size:13px;">{{ $dt->description }}</strong>
                                    <small style="display:block;color:var(--text-dim);font-size:11px;">{{ ($dt->tracked_at ?? $dt->created_at)->format('M d, Y h:i A') }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- ===== POST-DELIVERY REVIEW PROMPT (once) ===== --}}
                @if($order->delivery_status === 'delivered' && !$deliveryReviewDone)
                <div class="fp-detail-card mt-4 reveal-left" style="transition-delay:0.14s;border-color:rgba(74,222,128,0.3);">
                    <div class="fp-dc-header">
                        <h4><i class="bi bi-stars" style="color:#4ade80;"></i> How was your delivery?</h4>
                    </div>
                    <div class="fp-dc-body">
                        <form action="{{ route('orders.review', $order) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Delivery person</label>
                                    <div class="fp-star-input" data-target="delivery_rating">
                                        @for($s = 1; $s <= 5; $s++)
                                        <i class="bi bi-star" data-star="{{ $s }}" style="font-size:22px;cursor:pointer;color:var(--card-border);transition:all 0.2s;"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="delivery_rating" id="delivery_rating" value="5">
                                </div>
                                <div class="col-md-6">
                                    <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Product satisfaction</label>
                                    <div class="fp-star-input" data-target="product_rating">
                                        @for($s = 1; $s <= 5; $s++)
                                        <i class="bi bi-star" data-star="{{ $s }}" style="font-size:22px;cursor:pointer;color:var(--card-border);transition:all 0.2s;"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="product_rating" id="product_rating" value="5">
                                </div>
                                <div class="col-12">
                                    <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Comments (optional)</label>
                                    <input type="text" name="delivery_comment" class="fp-chk-select" style="padding:10px 12px;" placeholder="Anything to share about the delivery or the product?">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="fp-action-btn gold"><i class="bi bi-stars"></i> Submit feedback</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- ===== ORDER ITEMS ===== --}}
                <div class="fp-detail-card mt-4 reveal-left" style="transition-delay:0.15s;">
                    <div class="fp-dc-header"><h4><i class="bi bi-box-seam-fill"></i> Order Items</h4></div>
                    <div class="fp-dc-body p-0">
                        @foreach($order->items as $item)
                        <div class="fp-oi-row">
                            @if($item->product && $item->product->primaryImage)
                                <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" loading="lazy">
                            @else
                                <div class="fp-oi-no-img-sm"><i class="bi bi-image"></i></div>
                            @endif
                            <div class="fp-oi-detail">
                                <span class="fp-oi-name">{{ $item->product?->name ?? $item->product_name ?? 'Product' }}</span>
                                <span class="fp-oi-meta">Qty: {{ $item->quantity }} × ₦{{ number_format((float) $item->unit_price, 0) }}</span>
                            </div>
                            <span class="fp-oi-total">₦{{ number_format((float) $item->quantity * (float) $item->unit_price, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- ===== MAKE A PAYMENT ===== --}}
                <div class="fp-detail-card reveal-right">
                    <div class="fp-dc-header"><h4><i class="bi bi-credit-card-fill"></i> Make a Payment</h4></div>
                    <div class="fp-dc-body">
                        @if($order->remaining_amount > 0)
                            {{-- Pay next installment (incl. late fee if overdue) --}}
                            @if($nextPayment)
                            <form action="{{ route('orders.pay.installment', $order) }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="installment_payment_id" value="{{ $nextPayment->id }}">
                                <button type="submit" class="fp-action-btn gold w-100">
                                    <i class="bi bi-coin"></i> Pay Next Installment
                                </button>
                                <small style="display:block;color:var(--text-dim);font-size:12px;text-align:center;margin-top:6px;">
                                    Installment #{{ $nextPayment->installment_number }} · <span class="fp-mono">₦{{ number_format($nextDue, 2) }}</span>
                                    @if($nextLateFee > 0) <span style="color:#f87171;">(includes ₦{{ number_format($nextLateFee, 2) }} late fee)</span> @endif
                                </small>
                            </form>
                            @endif

                            {{-- Custom partial amount --}}
                            <form action="{{ route('orders.pay.partial', $order) }}" method="POST" class="mb-3">
                                @csrf
                                <label style="display:block;font-size:11px;color:var(--text-dim);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">
                                    Pay any amount
                                </label>
                                <div class="fp-partial-form">
                                    <input type="number" name="amount" min="100" max="{{ (float) $order->remaining_amount }}" step="0.01" placeholder="₦0.00" required>
                                    <button type="submit" class="fp-action-btn outline" style="padding:10px 16px;"><i class="bi bi-arrow-right"></i></button>
                                </div>
                            </form>

                            {{-- Wallet payment --}}
                            <form action="{{ route('orders.pay.wallet', $order) }}" method="POST" class="mb-3">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $nextDue > 0 ? $nextDue : (float) $order->remaining_amount }}">
                                <button type="submit" class="fp-action-btn wallet w-100">
                                    <i class="bi bi-wallet2"></i> Pay with Wallet
                                    <small style="font-weight:400;opacity:0.8;">(₦{{ number_format($walletBalance, 0) }} available)</small>
                                </button>
                            </form>

                            {{-- Pay off in one go --}}
                            <form action="{{ route('orders.pay.full', $order) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="fp-action-btn w-100"><i class="bi bi-check-all"></i> Pay Full Balance</button>
                            </form>
                        @else
                            <div style="text-align:center;padding:12px 0;">
                                <i class="bi bi-check-circle-fill" style="color:#4ade80;font-size:38px;display:block;margin-bottom:10px;"></i>
                                <p style="color:var(--text-primary);font-size:15px;font-weight:600;margin:0;">Fully paid — it's all yours!</p>
                                <p style="color:var(--text-dim);font-size:13px;margin-top:4px;">Nothing left to pay on this order.</p>
                            </div>
                        @endif

                        <div class="mt-3 pt-3" style="border-top:1px solid var(--card-border);">
                            <form action="{{ route('orders.change.plan', $order) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="fp-action-btn outline w-100"><i class="bi bi-arrow-repeat"></i> Change Plan</button>
                            </form>
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" id="cancelOrderForm">
                                @csrf
                                <input type="hidden" name="reason" id="cancelReason">
                                <input type="hidden" name="accept_fee" id="cancelAcceptFee" value="0">
                                <button type="button" class="fp-action-btn danger w-100" id="cancelOrderBtn"><i class="bi bi-x-circle"></i> Cancel Order</button>
                            </form>
                        </div>
                    </div>
                </div>

                @if($order->deliveryTrackings && $order->deliveryTrackings->count() > 0)
                <div class="fp-detail-card mt-4 reveal-right" style="transition-delay:0.1s;">
                    <div class="fp-dc-header"><h4><i class="bi bi-truck-front-fill"></i> Delivery Tracking</h4></div>
                    <div class="fp-dc-body">
                        @foreach($order->deliveryTrackings as $dt)
                        <div class="fp-tracking-item">
                            <div class="fp-tracking-dot {{ $dt->status }}"></div>
                            <div>
                                <strong style="color:var(--text-primary);font-size:13px;">{{ $dt->description }}</strong>
                                <small style="display:block;color:var(--text-dim);font-size:11px;">{{ $dt->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const swalDark = Swal.mixin({
        background: '#1A1A1E',
        color: '#F4F4F5',
        confirmButtonColor: '#EAB308',
        cancelButtonColor: '#52525B',
        backdrop: 'rgba(0,0,0,0.7)',
        customClass: {
            popup: 'swal-dark-popup',
            validationMessage: 'swal-dark-validation',
        }
    });

    @if(session('success'))
        swalDark.fire({ icon:'success', title:'Success!', text:"{{ session('success') }}" });
    @endif
    @if(session('error'))
        swalDark.fire({ icon:'error', title:'Error!', text:"{{ session('error') }}" });
    @endif
    @if(session('info'))
        swalDark.fire({ icon:'info', title:'Info', text:"{{ session('info') }}" });
    @endif

    // Star ratings for the post-delivery review prompt
    document.querySelectorAll('.fp-star-input').forEach(group => {
        const targetId = group.dataset.target;
        const input = document.getElementById(targetId);
        const stars = group.querySelectorAll('i[data-star]');
        const paint = (value) => {
            stars.forEach(s => s.classList.toggle('bi-star-fill', parseInt(s.dataset.star) <= value));
            stars.forEach(s => s.classList.toggle('bi-star', parseInt(s.dataset.star) > value));
        };
        stars.forEach(star => {
            star.addEventListener('mouseenter', () => paint(parseInt(star.dataset.star)));
            star.addEventListener('click', () => { input.value = star.dataset.star; paint(parseInt(star.dataset.star)); });
        });
        group.addEventListener('mouseleave', () => paint(parseInt(input.value || 5)));
    });

    document.getElementById('cancelOrderBtn')?.addEventListener('click', function() {
        swalDark.fire({
            title: 'Cancel Order?',
            html: `
                <p style="color:#A1A1AA;margin-bottom:16px;font-size:14px;">100% of what you've paid is refunded to your wallet as store credit.</p>
                <textarea id="swalCancelReason" placeholder="Why are you cancelling?" style="background:#121214;color:#F4F4F5;border:1px solid #3A3A3E;border-radius:8px;padding:10px 12px;width:100%;min-height:80px;resize:vertical;font-family:inherit;font-size:14px;outline:none;box-sizing:border-box;"></textarea>
            `,
            icon: 'warning',
            iconColor: '#facc15',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, cancel order',
            cancelButtonText: 'Keep order',
            width: '420px',
            padding: '24px',
            didOpen: () => {
                document.getElementById('swalCancelReason')?.focus();
            }
        }).then((r) => {
            if (r.isConfirmed) {
                document.getElementById('cancelReason').value = document.getElementById('swalCancelReason')?.value.trim() ?? '';
                document.getElementById('cancelOrderForm').submit();
            }
        });
    });
});
</script>
<style>
.swal-dark-popup { border:1px solid #2A2A2E;border-radius:16px !important; }
.swal-dark-popup .swal2-title { color:#F4F4F5;font-size:18px; }
.swal-dark-popup .swal2-textarea:focus { border-color:#EAB308 !important;box-shadow:0 0 0 2px rgba(234,179,8,0.15); }
.swal-dark-validation { background:#27272A;color:#ef4444;border-radius:6px;padding:6px 10px;font-size:12px;margin-top:8px; }
</style>
@endpush
