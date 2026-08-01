@extends('frontend.app')
@section('title', 'Track Order #'.$order->id.' — OwnPace Store')

@push('styles')
<style>
.fp-trk-hero {
    position: relative; padding: 40px 0 20px; overflow: hidden;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
}
.fp-trk-orb {
    position: absolute; width: 500px; height: 500px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -200px; left: -100px; pointer-events: none;
    animation: trkPulse 4s ease-in-out infinite;
}
@keyframes trkPulse { 0%,100%{transform:scale(1);opacity:0.5} 50%{transform:scale(1.1);opacity:1} }

.fp-trk-section { padding-bottom: 80px; min-height: 60vh; }

.fp-track-card {
    background:var(--card-dark);border:1px solid var(--card-border);
    border-radius:var(--radius);overflow:hidden;
    transition: border-color 0.3s; contain:layout style;
}
.fp-track-card:hover { border-color: rgba(234,179,8,0.15); }

.fp-track-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 24px; background: var(--surface-dark);
    border-bottom: 1px solid var(--card-border);
}
.fp-track-icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: rgba(234,179,8,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-500); font-size: 22px; flex-shrink: 0;
}
.fp-track-header h4 { font-family:'Syne',sans-serif; font-size:16px; font-weight:700; color:var(--text-primary); margin:0; }
.fp-status-badge { padding: 4px 14px; border-radius: 6px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
.fp-status-badge.pending { background: rgba(234,179,8,0.15); color: var(--gold-400); }
.fp-status-badge.processing { background: rgba(59,130,246,0.15); color: #60a5fa; }
.fp-status-badge.shipped { background: rgba(34,197,94,0.15); color: #4ade80; }
.fp-status-badge.delivered { background: rgba(34,197,94,0.15); color: #4ade80; }
.fp-track-price { font-family:'Syne',sans-serif; font-size: 22px; font-weight: 800; color: var(--gold-400); }

.fp-track-timeline { padding: 32px 24px; }
.fp-tl-item { display: flex; align-items: flex-start; gap: 16px; position: relative; padding-bottom: 32px; }
.fp-tl-item:last-child { padding-bottom: 0; }
.fp-tl-item::before {
    content:''; position:absolute; left:19px; top:42px; bottom:0; width:2px; background: var(--card-border);
}
.fp-tl-item.complete::before { background: linear-gradient(180deg, var(--gold-500), var(--gold-600)); }
.fp-tl-item:last-child::before { display: none; }
.fp-tl-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--card-border); display: flex; align-items: center;
    justify-content: center; color: var(--text-dim); font-size: 16px;
    flex-shrink: 0; z-index: 1; transition: all 0.3s;
}
.fp-tl-item.complete .fp-tl-icon { background: var(--gold-500); color: var(--near-black); }
.fp-tl-item.active .fp-tl-icon { background: var(--gold-500); color: var(--near-black); box-shadow: 0 0 0 6px rgba(234,179,8,0.15); animation: tlPulse 2s infinite; }
@keyframes tlPulse { 0%,100% { box-shadow: 0 0 0 6px rgba(234,179,8,0.15); } 50% { box-shadow: 0 0 0 10px rgba(234,179,8,0.05); } }

.fp-tl-content strong { display: block; color: var(--text-primary); font-size: 14px; }
.fp-tl-content small { color: var(--text-dim); font-size: 12px; }

.fp-track-updates { padding: 0 24px 24px; }
.fp-track-updates h5 { font-family:'Syne',sans-serif; font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
.fp-track-updates h5 i { color: var(--gold-500); }
.fp-tu-item { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; }
.fp-tu-dot { width: 10px; height: 10px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
.fp-tu-dot.completed { background: #4ade80; box-shadow: 0 0 0 3px rgba(34,197,94,0.15); }
.fp-tu-dot.pending { background: var(--card-border); }
.fp-tu-item strong { display: block; color: var(--text-primary); font-size: 13px; overflow-wrap: break-word; }
.fp-tu-item small { color: var(--text-dim); font-size: 11px; }

.fp-track-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 24px; background: var(--surface-dark);
    border-top: 1px solid var(--card-border); flex-wrap: wrap; gap: 12px;
}
.fp-btn-sm {
    padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--card-border); color: var(--text-muted);
    transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
}
.fp-btn-sm:hover { border-color: var(--gold-400); color: var(--gold-400); }
</style>
@endpush

@section('content')
<section class="fp-trk-hero">
    <div class="fp-trk-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-truck-front-fill"></i> Delivery Tracking</div>
            <h2>Track Order #{{ $order->id }}</h2>
            <p>Follow your order from processing to delivery</p>
        </div>
    </div>
</section>

<section class="fp-trk-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="fp-track-card reveal-up">
                    <div class="fp-track-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fp-track-icon"><i class="bi bi-truck-front-fill"></i></div>
                            <div>
                                <h4>Delivery Status</h4>
                                <span class="fp-status-badge {{ $order->delivery_status ?? 'pending' }}">
                                    {{ ucfirst($order->delivery_status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                        <div class="fp-track-price">₦{{ number_format($order->total, 0) }}</div>
                    </div>

                    <div class="fp-track-timeline">
                        @php
                            $statuses = [
                                ['key' => 'pending', 'icon' => 'bi-clock-fill', 'label' => 'Order Placed', 'time' => $order->created_at],
                                ['key' => 'processing', 'icon' => 'bi-gear-fill', 'label' => 'Processing', 'time' => $order->updated_at],
                                ['key' => 'shipped', 'icon' => 'bi-truck-front-fill', 'label' => 'Shipped', 'time' => null],
                                ['key' => 'delivered', 'icon' => 'bi-check-circle-fill', 'label' => 'Delivered', 'time' => null],
                            ];
                            $currentStatus = $order->delivery_status ?? 'pending';
                            $found = false;
                        @endphp

                        @foreach($statuses as $st)
                            @php
                                $isComplete = !$found;
                                if($st['key'] == $currentStatus) $found = true;
                                $isActive = $st['key'] == $currentStatus;
                            @endphp
                            <div class="fp-tl-item {{ ($isComplete && !$isActive) ? 'complete' : '' }} {{ $isActive ? 'active' : '' }}">
                                <div class="fp-tl-icon"><i class="bi {{ $st['icon'] }}"></i></div>
                                <div class="fp-tl-content">
                                    <strong>{{ $st['label'] }}</strong>
                                    @if($st['time'])
                                        <small>{{ $st['time']->format('M d, Y h:i A') }}</small>
                                    @elseif($isActive)
                                        <small style="color:var(--gold-400);">In progress...</small>
                                    @else
                                        <small style="color:var(--text-dim);">Pending</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($order->deliveryTrackings && $order->deliveryTrackings->count() > 0)
                    <div class="fp-track-updates">
                        <h5><i class="bi bi-clock-history"></i> Tracking Updates</h5>
                        @foreach($order->deliveryTrackings as $dt)
                        <div class="fp-tu-item">
                            <div class="fp-tu-dot {{ $dt->status }}"></div>
                            <div>
                                <strong>{{ $dt->description }}</strong>
                                <small>{{ $dt->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="fp-track-footer">
                        <p style="color:var(--text-dim);font-size:13px;margin:0;">
                            <i class="bi bi-info-circle-fill" style="color:var(--gold-500);"></i>
                            Once 70% is paid, your item becomes eligible for shipping
                        </p>
                        <a href="{{ route('orders.show', $order) }}" class="fp-btn-sm"><i class="bi bi-eye"></i> View Order Details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.footer')
@endsection
