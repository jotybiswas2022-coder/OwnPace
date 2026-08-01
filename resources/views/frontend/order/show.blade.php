@extends('frontend.app')
@section('title', 'Order #'.$order->id.' — OwnPace Store')

@push('styles')
<style>
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

.fp-order-status { padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;text-transform:uppercase; }
.fp-order-status.processing,.fp-order-status.partial_paid { background:rgba(234,179,8,0.15);color:var(--gold-400); }
.fp-order-status.completed { background:rgba(34,197,94,0.15);color:#4ade80; }
.fp-order-status.cancelled { background:rgba(239,68,68,0.15);color:#ef4444; }
.fp-order-status.shipped { background:rgba(59,130,246,0.15);color:#60a5fa; }

.fp-progress-bar-lg { height:10px;background:var(--card-border);border-radius:99px;overflow:hidden; }
.fp-progress-fill-lg { height:100%;background:linear-gradient(90deg,var(--gold-500),var(--gold-600));border-radius:99px;transition:width 0.5s; }
.fp-eligible-badge { display:flex;align-items:center;gap:5px;color:#4ade80;font-size:12px;font-weight:500; }

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

.fp-is-row { display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--card-border); }
.fp-is-row:last-child { border-bottom:none; }
.fp-is-row.paid { background:rgba(34,197,94,0.04); }

.fp-tracking-item { display:flex;align-items:flex-start;gap:12px;margin-bottom:14px; }
.fp-tracking-dot { width:12px;height:12px;border-radius:50%;margin-top:4px;flex-shrink:0; }
.fp-tracking-dot.completed { background:#4ade80;box-shadow:0 0 0 4px rgba(34,197,94,0.15); }
.fp-tracking-dot.pending { background:var(--card-border); }
.fp-tracking-dot.shipped { background:#60a5fa;box-shadow:0 0 0 4px rgba(59,130,246,0.15); }
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
                <div class="fp-detail-card reveal-left">
                    <div class="fp-dc-header">
                        <h4><i class="bi bi-receipt"></i> Order #{{ $order->id }}</h4>
                        <span class="fp-order-status {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="fp-dc-body">
                        <div class="row g-3">
                            <div class="col-md-4"><label>Order Date</label><span>{{ $order->created_at->format('M d, Y') }}</span></div>
                            <div class="col-md-4"><label>Total Amount</label><span class="fp-gold">₦{{ number_format($order->total, 0) }}</span></div>
                            <div class="col-md-4"><label>Paid Amount</label><span class="fp-green">₦{{ number_format($order->paid_amount ?? 0, 0) }}</span></div>
                            <div class="col-md-4"><label>Remaining</label><span>₦{{ number_format($order->remaining_balance ?? $order->total, 0) }}</span></div>
                            <div class="col-md-4"><label>Payment Plan</label><span>{{ $order->installmentPlan?->duration ?? 'N/A' }} {{ $order->installmentPlan?->duration_unit ?? '' }}</span></div>
                            <div class="col-md-4"><label>Delivery Status</label><span>{{ ucfirst($order->delivery_status ?? 'pending') }}</span></div>
                        </div>

                        @php $pct = $order->total > 0 ? round((($order->paid_amount ?? 0) / $order->total) * 100) : 0; @endphp
                        <div class="fp-progress-section mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span style="color:var(--text-muted);font-size:13px;">Payment Progress</span>
                                <span style="color:var(--gold-400);font-size:13px;font-weight:600;">{{ $pct }}%</span>
                            </div>
                            <div class="fp-progress-bar-lg" role="progressbar" aria-label="Payment progress" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"><div class="fp-progress-fill-lg" style="width:{{ $pct }}%"></div></div>
                            @if($pct >= 70)
                            <div class="fp-eligible-badge mt-2"><i class="bi bi-check-circle-fill"></i> Eligible for shipping!</div>
                            @else
                            <div style="color:var(--text-dim);font-size:12px;margin-top:4px;">Pay 70% to qualify for shipping</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="fp-detail-card mt-4 reveal-left" style="transition-delay:0.1s;">
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
                                <span class="fp-oi-name">{{ $item->product?->name ?? 'Product' }}</span>
                                <span class="fp-oi-meta">Qty: {{ $item->quantity }} × ₦{{ number_format($item->price, 0) }}</span>
                            </div>
                            <span class="fp-oi-total">₦{{ number_format($item->quantity * $item->price, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="fp-detail-card reveal-right">
                    <div class="fp-dc-header"><h4><i class="bi bi-credit-card-fill"></i> Make a Payment</h4></div>
                    <div class="fp-dc-body">
                        <form action="{{ route('orders.pay.full', $order) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="fp-action-btn gold w-100"><i class="bi bi-check-all"></i> Pay Full Balance</button>
                        </form>
                        <form action="{{ route('orders.pay.installment', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="fp-action-btn w-100"><i class="bi bi-coin"></i> Pay Next Installment</button>
                        </form>
                        <div class="mt-3">
                            <form action="{{ route('orders.change.plan', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="fp-action-btn outline w-100"><i class="bi bi-arrow-repeat"></i> Change Plan</button>
                            </form>
                        </div>
                        <div class="mt-2">
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" id="cancelOrderForm">
                                @csrf
                                <input type="hidden" name="reason" id="cancelReason">
                                <input type="hidden" name="accept_fee" id="cancelAcceptFee" value="0">
                                <button type="button" class="fp-action-btn danger w-100" id="cancelOrderBtn"><i class="bi bi-x-circle"></i> Cancel Order</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="fp-detail-card mt-4 reveal-right" style="transition-delay:0.1s;">
                    <div class="fp-dc-header"><h4><i class="bi bi-calendar-check"></i> Installment Schedule</h4></div>
                    <div class="fp-dc-body p-0">
                        @if($order->installmentPayments && $order->installmentPayments->count() > 0)
                        @foreach($order->installmentPayments as $ip)
                        <div class="fp-is-row {{ $ip->status == 'paid' ? 'paid' : '' }}">
                            <div>
                                <strong style="color:var(--text-primary);font-size:13px;">Installment #{{ $ip->installment_number }}</strong>
                                <small style="display:block;color:var(--text-dim);font-size:11px;">Due: {{ $ip->due_date->format('M d, Y') }}</small>
                            </div>
                            <div class="text-end">
                                <span style="font-weight:600;color:var(--gold-400);">₦{{ number_format($ip->amount, 0) }}</span>
                                <span style="display:block;font-size:11px;color:{{ $ip->status == 'paid' ? '#4ade80' : 'var(--text-dim)' }};">
                                    {{ ucfirst($ip->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="text-center py-4" style="color:var(--text-dim);font-size:13px;">No installment schedule yet</div>
                        @endif
                    </div>
                </div>

                @if($order->deliveryTrackings && $order->deliveryTrackings->count() > 0)
                <div class="fp-detail-card mt-4 reveal-right" style="transition-delay:0.2s;">
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

    document.getElementById('cancelOrderBtn')?.addEventListener('click', function() {
        swalDark.fire({
            title: 'Cancel Order?',
            html: `
                <p style="color:#A1A1AA;margin-bottom:16px;font-size:14px;">A 10% cancellation fee will apply.</p>
                <textarea id="swalCancelReason" placeholder="Why are you cancelling? (min 10 chars)" style="background:#121214;color:#F4F4F5;border:1px solid #3A3A3E;border-radius:8px;padding:10px 12px;width:100%;min-height:80px;resize:vertical;font-family:inherit;font-size:14px;outline:none;box-sizing:border-box;"></textarea>
                <label style="display:flex;align-items:center;gap:8px;margin-top:14px;color:#A1A1AA;font-size:13px;cursor:pointer;user-select:none;">
                    <input type="checkbox" id="swalAcceptFee" style="accent-color:#EAB308;width:16px;height:16px;flex-shrink:0;">
                    I accept the 10% cancellation fee
                </label>
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
            },
            preConfirm: () => {
                const reason = document.getElementById('swalCancelReason')?.value.trim();
                const accept = document.getElementById('swalAcceptFee')?.checked;

                if (!accept) {
                    Swal.showValidationMessage('Please accept the cancellation fee');
                    return false;
                }
                return { reason, acceptFee: accept };
            }
        }).then((r) => {
            if (r.isConfirmed) {
                document.getElementById('cancelReason').value = r.value.reason;
                document.getElementById('cancelAcceptFee').value = r.value.acceptFee ? '1' : '0';
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
