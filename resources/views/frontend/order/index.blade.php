@extends('frontend.app')
@section('title', 'My Orders — OwnPace Store')

@push('styles')
<style>
/* ===== ORDERS HERO ===== */
.fp-ord-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-ord-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-ord-orb {
    position: absolute; width: 500px; height: 500px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.05) 0%, transparent 60%);
    top: -200px; right: -100px; pointer-events: none;
    animation: ordPulse 6s ease-in-out infinite;
}
.fp-ord-orb2 {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.03) 0%, transparent 60%);
    bottom: -150px; left: -100px; pointer-events: none;
    animation: ordPulse 7s ease-in-out infinite reverse;
}
@keyframes ordPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-ord-section { padding-bottom: 80px; min-height: 60vh; }

/* ===== FILTER TABS ===== */
.fp-filter-tabs {
    display: flex; gap: 6px; flex-wrap: wrap;
    padding: 6px; background: var(--surface-dark);
    border: 1px solid var(--card-border); border-radius: 12px;
}
.fp-tab {
    padding: 9px 18px; border-radius: 8px;
    color: var(--text-muted); font-size: 13px; font-weight: 500;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
}
.fp-tab i { font-size: 12px; }
.fp-tab:hover { background: rgba(234,179,8,0.08); color: var(--gold-400); }
.fp-tab.active {
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); font-weight: 700;
    box-shadow: var(--shadow-gold);
}

/* ===== ORDER CARD ===== */
.fp-order-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); overflow: hidden; margin-bottom: 16px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
}
.fp-order-card::before {
    content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%;
    background: var(--card-border);
    transition: background 0.3s;
}
.fp-order-card:hover::before { background: var(--gold-500); }
.fp-order-card:hover {
    border-color: rgba(234,179,8,0.25);
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.3), var(--shadow-glow-sm);
}

.fp-order-header, .fp-order-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px 16px 23px; background: var(--surface-dark);
}
.fp-order-header { border-bottom: 1px solid var(--card-border); }
.fp-oh-left, .fp-oh-right { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.fp-order-id {
    font-weight: 700; color: var(--text-primary); font-size: 14px;
    display: flex; align-items: center; gap: 6px;
}
.fp-order-id i { color: var(--gold-500); font-size: 13px; }
.fp-order-date {
    color: var(--text-dim); font-size: 12px;
    display: flex; align-items: center; gap: 4px;
}
.fp-order-date i { font-size: 11px; }

.fp-order-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 14px; border-radius: 8px;
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.3px;
}
.fp-order-status i { font-size: 11px; }
.fp-order-status.pending { background: rgba(234,179,8,0.12); color: var(--gold-400); }
.fp-order-status.processing, .fp-order-status.partial_paid { background: rgba(234,179,8,0.12); color: var(--gold-400); }
.fp-order-status.completed { background: rgba(34,197,94,0.12); color: #4ade80; }
.fp-order-status.cancelled { background: rgba(239,68,68,0.12); color: #f87171; }
.fp-order-status.shipped { background: rgba(59,130,246,0.12); color: #60a5fa; }
.fp-order-amount {
    font-weight: 700; color: var(--gold-400);
    font-family: 'Syne', sans-serif; font-size: 16px;
}

/* ===== ORDER BODY ===== */
.fp-order-body { padding: 16px 20px 16px 23px; }
.fp-order-item {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 10px; padding: 8px 12px;
    border-radius: 8px; transition: background 0.2s;
}
.fp-order-item:hover { background: rgba(255,255,255,0.02); }
.fp-order-item:last-child { margin-bottom: 0; }
.fp-order-item img {
    width: 52px; height: 52px; border-radius: 8px;
    object-fit: cover; background: var(--dark-900);
    flex-shrink: 0;
}
.fp-oi-no-img {
    width: 52px; height: 52px; border-radius: 8px;
    background: var(--dark-900);
    display: flex; align-items: center; justify-content: center;
    color: var(--card-border); flex-shrink: 0;
}
.fp-oi-info { min-width: 0; }
.fp-oi-info span {
    display: block; color: var(--text-primary);
    font-size: 13px; font-weight: 500;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.fp-oi-info small { color: var(--text-dim); font-size: 12px; }
.fp-order-more {
    color: var(--text-dim); font-size: 12px;
    padding: 6px 12px 0 64px; font-weight: 500;
}

/* ===== ORDER FOOTER ===== */
.fp-order-footer { border-top: 1px solid var(--card-border); flex-wrap: wrap; gap: 12px; }
.fp-of-progress {
    display: flex; align-items: center; gap: 10px;
    flex: 1; min-width: 160px;
}
.fp-of-progress span {
    font-size: 12px; color: var(--text-dim);
    white-space: nowrap; font-weight: 600;
}
.fp-progress-bar {
    flex: 1; max-width: 140px; height: 6px;
    background: var(--card-border); border-radius: 99px; overflow: hidden;
}
.fp-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--gold-500), var(--gold-600));
    border-radius: 99px;
    transition: width 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.fp-of-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.fp-btn-sm {
    padding: 9px 18px; border-radius: 8px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--card-border); color: var(--text-muted);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none; display: inline-flex;
    align-items: center; gap: 6px;
    touch-action: manipulation; background: transparent;
    font-family: inherit; cursor: pointer;
}
.fp-btn-sm:hover {
    border-color: rgba(234,179,8,0.3);
    color: var(--gold-400);
    background: rgba(234,179,8,0.04);
}
.fp-btn-sm.gold {
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); border-color: transparent;
    position: relative; overflow: hidden;
}
.fp-btn-sm.gold::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.15) 50%, transparent 80%);
    transform: translateX(-100%); transition: transform 0.5s;
}
.fp-btn-sm.gold:hover::before { transform: translateX(100%); }
.fp-btn-sm.gold:hover {
    box-shadow: var(--shadow-gold);
    transform: translateY(-2px);
    color: var(--near-black);
}

/* ===== EMPTY ===== */
.fp-ord-empty {
    text-align: center; padding: 80px 20px;
}
.fp-ord-empty-icon {
    width: 100px; height: 100px; border-radius: 50%;
    background: var(--card-dark); border: 2px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px; font-size: 40px; color: var(--text-dim);
    transition: all 0.3s;
}
.fp-ord-empty:hover .fp-ord-empty-icon {
    border-color: rgba(234,179,8,0.2);
    transform: scale(1.05);
}
.fp-ord-empty h3 {
    font-family: 'Syne', sans-serif;
    color: var(--text-primary); font-size: 24px; margin-bottom: 8px;
}
.fp-ord-empty p { color: var(--text-muted); font-size: 15px; margin-bottom: 24px; }

/* ===== PAGINATION ===== */
.fp-pagination-custom { margin-top: 48px; }
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
    .fp-ord-hero { padding: 36px 0 20px; }
    .fp-order-header { flex-direction: column; align-items: flex-start; gap: 8px; }
    .fp-order-footer { flex-direction: column; align-items: flex-start; }
    .fp-of-progress { width: 100%; }
    .fp-progress-bar { max-width: 100%; flex: 1; }
    .fp-of-actions { width: 100%; }
    .fp-btn-sm { flex: 1; justify-content: center; }
    .fp-pagination-custom .page-item .page-link {
        min-width: 36px; height: 36px;
        padding: 6px 10px; font-size: 13px;
    }
}
@media (max-width: 576px) {
    .fp-filter-tabs { justify-content: stretch; }
    .fp-tab { flex: 1; justify-content: center; text-align: center; font-size: 12px; padding: 8px 10px; }
    .fp-order-body { padding: 12px 16px; }
    .fp-oh-right { width: 100%; justify-content: space-between; }
}
</style>
@endpush

@section('content')
<section class="fp-ord-hero">
    <div class="fp-ord-hero-grid" aria-hidden="true"></div>
    <div class="fp-ord-orb" aria-hidden="true"></div>
    <div class="fp-ord-orb2" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-box-seam-fill"></i> My Orders</div>
            <h2>Order History</h2>
            <p>Track and manage all your purchases</p>
        </div>
    </div>
</section>

<section class="fp-ord-section">
    <div class="container">
        @if(isset($orders) && $orders->count() > 0)
        <div class="fp-filter-tabs reveal-up mb-4">
            <a href="{{ route('orders.index') }}" class="fp-tab {{ !request('status') ? 'active' : '' }}"><i class="bi bi-grid-fill"></i> All</a>
            <a href="{{ route('orders.index', ['status' => 'processing']) }}" class="fp-tab {{ request('status') == 'processing' ? 'active' : '' }}"><i class="bi bi-arrow-repeat"></i> Processing</a>
            <a href="{{ route('orders.index', ['status' => 'shipped']) }}" class="fp-tab {{ request('status') == 'shipped' ? 'active' : '' }}"><i class="bi bi-truck"></i> Shipped</a>
            <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="fp-tab {{ request('status') == 'completed' ? 'active' : '' }}"><i class="bi bi-check-circle-fill"></i> Completed</a>
            <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="fp-tab {{ request('status') == 'cancelled' ? 'active' : '' }}"><i class="bi bi-x-circle-fill"></i> Cancelled</a>
        </div>

        @foreach($orders as $index => $order)
        <div class="fp-order-card reveal-up" style="transition-delay:{{ $index * 0.05 }}s;">
            <div class="fp-order-header">
                <div class="fp-oh-left">
                    <span class="fp-order-id"><i class="bi bi-receipt"></i> Order #{{ $order->id }}</span>
                    <span class="fp-order-date"><i class="bi bi-calendar3"></i> {{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="fp-oh-right">
                    <span class="fp-order-status {{ $order->status }}" aria-label="Status: {{ $order->status }}">
                        @php $statusIcons = ['pending'=>'bi-clock','processing'=>'bi-arrow-repeat','partial_paid'=>'bi-coin','completed'=>'bi-check-circle-fill','shipped'=>'bi-truck','cancelled'=>'bi-x-circle-fill']; @endphp
                        <i class="bi {{ $statusIcons[$order->status] ?? 'bi-circle' }}"></i>
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                    <span class="fp-order-amount">₦{{ number_format($order->total, 0) }}</span>
                </div>
            </div>
            <div class="fp-order-body">
                @foreach($order->items->take(3) as $item)
                <div class="fp-order-item">
                    @if($item->product && $item->product->primaryImage)
                        <img src="{{ asset('storage/'.$item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" loading="lazy" decoding="async">
                    @else
                        <div class="fp-oi-no-img"><i class="bi bi-image"></i></div>
                    @endif
                    <div class="fp-oi-info">
                        <span>{{ $item->product?->name ?? 'Product' }}</span>
                        <small>Qty: {{ $item->quantity }} × ₦{{ number_format($item->price, 0) }}</small>
                    </div>
                </div>
                @endforeach
                @if($order->items->count() > 3)
                <div class="fp-order-more">+{{ $order->items->count() - 3 }} more items</div>
                @endif
            </div>
            <div class="fp-order-footer">
                <div class="fp-of-progress">
                    @php
                        $paid = $order->total - ($order->remaining_balance ?? $order->total);
                        $pct = $order->total > 0 ? round(($paid / $order->total) * 100) : 0;
                    @endphp
                    <span>{{ $pct }}% paid</span>
                    <div class="fp-progress-bar"><div class="fp-progress-fill" style="width:{{ $pct }}%"></div></div>
                </div>
                <div class="fp-of-actions">
                    <a href="{{ route('orders.show', $order) }}" class="fp-btn-sm" aria-label="View details for order #{{ $order->id }}"><i class="bi bi-eye"></i> View Details</a>
                    @if($order->status == 'processing' || $order->status == 'partial_paid')
                    <a href="{{ route('payment.gateway', $order) }}" class="fp-btn-sm gold" aria-label="Pay now for order #{{ $order->id }}"><i class="bi bi-credit-card"></i> Pay Now</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        @if(method_exists($orders, 'links'))
        <div class="fp-pagination-custom mt-4">{{ $orders->links() }}</div>
        @endif
        @else
        <div class="fp-ord-empty reveal-up">
            <div class="fp-ord-empty-icon"><i class="bi bi-inbox"></i></div>
            <h3>No Orders Yet</h3>
            <p>You haven't placed any orders yet. Start shopping and your orders will appear here!</p>
            <a href="{{ url('/shop') }}" class="btn-primary-gold" style="display:inline-flex;"><i class="bi bi-grid-fill"></i> Start Shopping</a>
        </div>
        @endif
    </div>
</section>

@include('frontend.partials.footer')
@endsection
