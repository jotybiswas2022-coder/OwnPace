@extends('frontend.app')
@section('title', 'Exchange Product — Order #'.$order->id)

@push('styles')
<style>
.fp-ex-hero {
    position: relative; padding: 34px 0 22px; overflow: hidden;
    background: linear-gradient(180deg, rgba(234,179,8,0.03) 0%, transparent 100%);
    border-bottom: 1px solid var(--card-border);
}
.fp-ex-section { padding-bottom: 80px; min-height: 60vh; }
.fp-breadcrumb { display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-dim); }
.fp-breadcrumb a { color: var(--gold-400); }
.fp-breadcrumb i { font-size:11px; }

.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
.fp-alert.error { background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.25);color:#f87171; }
.fp-alert.info { background:rgba(59,130,246,0.08);border-color:rgba(59,130,246,0.25);color:#60a5fa; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-ex-card { background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius);overflow:hidden;transition:all 0.3s; }
.fp-ex-card:hover { border-color:rgba(234,179,8,0.15); }
.fp-ex-header { display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--card-border); }
.fp-ex-header h4 { font-family:'Syne',sans-serif;font-size:15px;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:8px;margin:0; }
.fp-ex-header h4 i { color:var(--gold-500); }
.fp-ex-body { padding:20px; }
.fp-ex-body label { display:block;font-size:11px;color:var(--text-dim);font-weight:500;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px; }

.fp-product-chip {
    display:flex; align-items:center; gap:12px;
    background:var(--surface-dark); border:1px solid var(--card-border);
    border-radius:var(--radius-sm); padding:12px 14px;
}
.fp-product-chip img { width:48px; height:48px; border-radius:8px; object-fit:cover; background:var(--dark-900); }
.fp-product-chip .fp-pc-noimg { width:48px; height:48px; border-radius:8px; background:var(--dark-900); display:flex; align-items:center; justify-content:center; color:var(--card-border); }
.fp-product-chip strong { display:block; color:var(--text-primary); font-size:13px; font-weight:600; }
.fp-product-chip small { color:var(--text-dim); font-size:12px; }

.fp-wl-option {
    position: relative; display:flex; align-items:center; gap:12px;
    background:var(--surface-dark); border:1.5px solid var(--card-border);
    border-radius:var(--radius-sm); padding:12px;
    cursor:pointer; transition:all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-wl-option:hover { border-color:rgba(234,179,8,0.3); transform:translateY(-2px); }
.fp-wl-option input { position:absolute; opacity:0; pointer-events:none; }
.fp-wl-option.checked { border-color:var(--gold-500); background:rgba(234,179,8,0.05); }
.fp-wl-option img { width:56px; height:56px; border-radius:8px; object-fit:cover; background:var(--dark-900); flex-shrink:0; }
.fp-wl-option .fp-wl-noimg { width:56px; height:56px; border-radius:8px; background:var(--dark-900); display:flex; align-items:center; justify-content:center; color:var(--card-border); flex-shrink:0; }
.fp-wl-info { flex:1; min-width:0; }
.fp-wl-info strong { display:block; color:var(--text-primary); font-size:13px; font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.fp-wl-info small { color:var(--text-dim); font-size:12px; }
.fp-wl-radio {
    width:22px; height:22px; border-radius:50%; flex-shrink:0;
    border:2px solid var(--card-border); display:flex; align-items:center; justify-content:center;
    transition:all 0.3s;
}
.fp-wl-option.checked .fp-wl-radio { border-color:var(--gold-500); }
.fp-wl-option.checked .fp-wl-radio::after { content:''; width:10px; height:10px; border-radius:50%; background:var(--gold-500); }
.fp-wl-price { font-family:'Syne',sans-serif; color:var(--gold-400); font-weight:700; font-size:14px; flex-shrink:0; }

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

.fp-ex-empty { text-align:center; padding:40px 20px; }
.fp-ex-empty-icon { width:72px; height:72px; border-radius:20px; background:var(--surface-dark); border:1px solid var(--card-border); display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:28px; color:var(--text-dim); }
.fp-ex-empty p { color:var(--text-muted); font-size:14px; margin-bottom:16px; }

@media (max-width: 768px) {
    .fp-ex-hero { padding: 24px 0 16px; }
}
</style>
@endpush

@section('content')
<section class="fp-ex-hero">
    <div class="container">
        <nav class="fp-breadcrumb reveal-up">
            <a href="{{ url('/') }}">Home</a><i class="bi bi-chevron-right"></i>
            <a href="{{ route('orders.index') }}">Orders</a><i class="bi bi-chevron-right"></i>
            <a href="{{ route('orders.show', $order) }}">Order #{{ $order->id }}</a><i class="bi bi-chevron-right"></i>
            <span>Exchange Product</span>
        </nav>
    </div>
</section>

<section class="fp-ex-section">
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
                    You already have a pending exchange request for this order. It's waiting for admin review.
                </div>
                @endif

                <div class="fp-ex-card reveal-left">
                    <div class="fp-ex-header">
                        <h4><i class="bi bi-arrow-left-right"></i> Exchange a Product</h4>
                        <span style="color:var(--gold-400);font-weight:700;font-size:13px;">Order #{{ $order->id }}</span>
                    </div>
                    <div class="fp-ex-body">
                        <label>You'll swap</label>
                        @if($currentProduct)
                        <div class="fp-product-chip mb-4">
                            @if($currentProduct->primaryImage)
                                <img src="{{ asset('storage/'.$currentProduct->primaryImage->image_path) }}" alt="{{ $currentProduct->name }}">
                            @else
                                <div class="fp-pc-noimg"><i class="bi bi-image"></i></div>
                            @endif
                            <div>
                                <strong>{{ $currentProduct->name }}</strong>
                                <small>From your order — swap it for something from your wishlist</small>
                            </div>
                        </div>
                        @else
                        <div class="fp-alert info mb-4" style="margin-bottom:16px;">
                            <i class="bi bi-info-circle-fill"></i> We couldn't find the product on this order — please contact support.
                        </div>
                        @endif

                        <form method="POST" action="{{ route('orders.exchange.request', $order) }}" id="exchangeForm">
                            @csrf
                            <label style="margin-bottom:8px;">Choose a wishlist item to swap for</label>
                            <div class="row g-3">
                                @forelse($wishlist as $product)
                                <div class="col-md-6">
                                    <label class="fp-wl-option w-100" for="wl-{{ $product->id }}">
                                        <input type="radio" name="product_id" id="wl-{{ $product->id }}" value="{{ $product->id }}" required {{ $loop->first ? 'checked' : '' }}>
                                        @if($product->primaryImage)
                                            <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                                        @else
                                            <div class="fp-wl-noimg"><i class="bi bi-image"></i></div>
                                        @endif
                                        <span class="fp-wl-info">
                                            <strong>{{ Str::limit($product->name, 40) }}</strong>
                                            <small>In your wishlist</small>
                                        </span>
                                        <span class="fp-wl-radio"></span>
                                        <span class="fp-wl-price">₦{{ number_format((float) $product->price, 0) }}</span>
                                    </label>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="fp-ex-empty">
                                        <div class="fp-ex-empty-icon"><i class="bi bi-heartbreak-fill"></i></div>
                                        <p>Your wishlist is empty — add the product you'd like to swap for first.</p>
                                        <a href="{{ url('/shop') }}" class="btn-primary-gold" style="display:inline-flex;"><i class="bi bi-grid-fill"></i> Browse Products</a>
                                    </div>
                                </div>
                                @endforelse
                            </div>

                            <label style="margin:18px 0 8px;">Why do you want to exchange?</label>
                            <textarea name="reason" class="fp-input" rows="3" minlength="10" required
                                      placeholder="Tell us why you'd like to swap (at least 10 characters)"></textarea>

                            <div class="d-flex align-items-center mt-4" style="gap:10px;">
                                <button type="submit" class="fp-submit-btn" {{ $wishlist->isEmpty() || (isset($pendingRequest) && $pendingRequest) ? 'disabled' : '' }}>
                                    <i class="bi bi-send-fill"></i> Submit Exchange Request
                                </button>
                                <a href="{{ route('orders.show', $order) }}" class="fp-cancel-link"><i class="bi bi-arrow-left"></i> Back to order</a>
                            </div>
                            <p class="mt-3" style="font-size:12px;color:var(--text-dim);">
                                <i class="bi bi-clock-history" style="color:var(--gold-500);"></i>
                                Your exchange stays pending until an admin approves it. Your current product stays with you until then.
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
    document.querySelectorAll('.fp-wl-option').forEach(opt => {
        const input = opt.querySelector('input');
        const sync = () => opt.classList.toggle('checked', input.checked);
        sync();
        opt.addEventListener('click', () => { input.checked = true; document.querySelectorAll('.fp-wl-option').forEach(o => o.classList.remove('checked')); opt.classList.add('checked'); });
    });
});
</script>
@endpush
