@extends('frontend.app')
@section('title', 'My Wishlist — OwnPace Store')

@push('styles')
<style>
/* ===== WISHLIST HERO ===== */
.fp-wl-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-wl-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-wl-orb {
    position: absolute; width: 500px; height: 500px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -200px; left: -100px; pointer-events: none;
    animation: wlOrbPulse 6s ease-in-out infinite;
}
.fp-wl-orb2 {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.03) 0%, transparent 60%);
    bottom: -150px; right: -100px; pointer-events: none;
    animation: wlOrbPulse 7s ease-in-out infinite reverse;
}
@keyframes wlOrbPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-wl-section { padding-bottom: 80px; min-height: 60vh; }

/* ===== GRID ===== */
.fp-wl-grid { display: grid; gap: 20px; }
.fp-wl-grid-4 { grid-template-columns: repeat(4, 1fr); }

/* ===== CARD ===== */
.fp-wl-card {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius); overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative; min-width: 0;
    display: flex; flex-direction: column;
}
.fp-wl-card::after {
    content: ''; position: absolute; inset: 0;
    border-radius: var(--radius);
    pointer-events: none; opacity: 0;
    transition: opacity 0.4s;
    box-shadow: inset 0 0 0 1px rgba(234,179,8,0.15);
}
.fp-wl-card:hover::after { opacity: 1; }
.fp-wl-card:hover {
    border-color: rgba(234,179,8,0.35);
    transform: translateY(-6px);
    box-shadow: 0 16px 48px rgba(234,179,8,0.1);
}

.fp-wl-img-wrap {
    position: relative; height: 200px;
    background: var(--dark-900); overflow: hidden;
}
.fp-wl-img-wrap img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-wl-card:hover .fp-wl-img-wrap img { transform: scale(1.12); }
.fp-wl-no-img {
    width: 100%; height: 100%; display: flex;
    align-items: center; justify-content: center;
    color: var(--card-border); font-size: 36px;
}

.fp-wl-badge {
    position: absolute; top: 10px; left: 10px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white; font-size: 10px; font-weight: 700;
    padding: 4px 10px; border-radius: 6px; z-index: 2;
    box-shadow: 0 2px 10px rgba(239,68,68,0.3);
}

.fp-wl-remove-top {
    position: absolute; top: 10px; right: 10px;
    width: 34px; height: 34px; border-radius: 50%;
    background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.1); color: white;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all 0.3s; font-size: 13px; z-index: 3;
    touch-action: manipulation; text-decoration: none;
}
.fp-wl-remove-top:hover { background: #ef4444; transform: scale(1.1) rotate(90deg); border-color: #ef4444; }

.fp-wl-overlay {
    position: absolute; inset: 0; background: rgba(0,0,0,0.5);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.3s ease; z-index: 2;
    backdrop-filter: blur(2px);
}
.fp-wl-card:hover .fp-wl-overlay { opacity: 1; }
.fp-wl-view-btn {
    padding: 10px 20px; border-radius: 8px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); font-size: 13px; font-weight: 700;
    border: none; cursor: pointer;
    display: flex; align-items: center; gap: 6px;
    transform: translateY(15px) scale(0.95);
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family: inherit; box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}
.fp-wl-card:hover .fp-wl-view-btn { transform: translateY(0) scale(1); }

.fp-wl-body {
    padding: 16px 16px 0;
    display: flex; flex-direction: column; gap: 6px;
    flex: 1;
}
.fp-wl-category {
    font-size: 11px; font-weight: 600; color: var(--gold-500);
    text-transform: uppercase; letter-spacing: 0.5px;
}
.fp-wl-name {
    color: var(--text-primary); font-size: 14px; font-weight: 600;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden; line-height: 1.4;
}
.fp-wl-price-row {
    display: flex; align-items: center; gap: 8px;
    margin-top: auto; padding-top: 8px;
}
.fp-wl-price {
    color: var(--gold-400); font-weight: 700;
    font-size: 17px; font-family: 'Syne', sans-serif;
}
.fp-wl-price-old {
    color: var(--text-dim); font-size: 13px; text-decoration: line-through;
}
.fp-wl-installment {
    font-size: 11px; color: var(--text-dim);
    padding: 0 16px;
}
.fp-wl-installment strong { color: var(--gold-400); }

/* ===== FOOTER ACTIONS ===== */
.fp-wl-footer {
    padding: 12px 16px 16px;
    display: flex; flex-direction: column; gap: 8px;
}
.fp-wl-footer form { margin: 0; }
.fp-wl-cart-btn {
    width: 100%; padding: 12px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); border: none;
    border-radius: var(--radius-sm); font-size: 13px; font-weight: 700;
    cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 6px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family: inherit;
}
.fp-wl-cart-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-gold);
}
.fp-wl-cart-btn i { font-size: 15px; }

.fp-wl-remove-btn {
    width: 100%; padding: 10px;
    background: transparent;
    border: 1px solid rgba(239,68,68,0.2);
    color: #f87171; border-radius: var(--radius-sm);
    font-size: 12px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    transition: all 0.3s; font-family: inherit;
}
.fp-wl-remove-btn:hover {
    background: rgba(239,68,68,0.08);
    border-color: #ef4444;
    color: #ef4444;
}

/* ===== ALERT ===== */
.fp-alert {
    display: flex; align-items: center; gap: 10px;
    background: rgba(34,197,94,0.08);
    border: 1px solid rgba(34,197,94,0.2);
    color: #4ade80; padding: 14px 20px;
    border-radius: var(--radius-sm);
    font-weight: 500; font-size: 13px; margin-bottom: 24px;
    animation: alertSlide 0.4s ease-out;
}
@keyframes alertSlide {
    from { opacity:0; transform: translateY(-10px); }
    to { opacity:1; transform: translateY(0); }
}

/* ===== EMPTY ===== */
.fp-wl-empty {
    text-align: center; padding: 80px 20px;
}
.fp-wl-empty-icon {
    width: 100px; height: 100px; border-radius: 50%;
    background: var(--card-dark); border: 2px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px; font-size: 40px; color: var(--text-dim);
    transition: all 0.3s;
    animation: emptyPulse 3s ease-in-out infinite;
}
.fp-wl-empty:hover .fp-wl-empty-icon {
    border-color: rgba(234,179,8,0.2);
}
@keyframes emptyPulse {
    0%,100% { transform: scale(1); opacity: 0.6; }
    50% { transform: scale(1.05); opacity: 1; }
}
.fp-wl-empty h3 {
    font-family: 'Syne', sans-serif;
    color: var(--text-primary); font-size: 24px; margin-bottom: 8px;
}
.fp-wl-empty p {
    color: var(--text-muted); font-size: 15px;
    margin-bottom: 24px; max-width: 400px;
    margin-left: auto; margin-right: auto;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1199px) {
    .fp-wl-grid-4 { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 767px) {
    .fp-wl-hero { padding: 36px 0 20px; }
    .fp-wl-grid-4 { grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .fp-wl-img-wrap { height: 160px; }
}
@media (max-width: 480px) {
    .fp-wl-grid-4 { grid-template-columns: 1fr; }
    .fp-wl-footer { flex-direction: row; }
    .fp-wl-footer .fp-wl-cart-btn { font-size: 12px; padding: 10px; }
}
</style>
@endpush

@section('content')
<section class="fp-wl-hero">
    <div class="fp-wl-hero-grid" aria-hidden="true"></div>
    <div class="fp-wl-orb" aria-hidden="true"></div>
    <div class="fp-wl-orb2" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-heart-fill"></i> My Wishlist</div>
            <h2>Saved Items</h2>
            <p>Items you love, ready when you are</p>
        </div>
    </div>
</section>

<section class="fp-wl-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif

        @if(isset($wishlist) && $wishlist->count() > 0)
        <div class="fp-wl-grid fp-wl-grid-4" id="wlGrid">
            @foreach($wishlist as $index => $item)
            @php $product = $item->product; @endphp
            @if($product)
            <div class="fp-wl-card reveal-up" style="transition-delay:{{ $index * 0.05 }}s;" data-tilt="8">
                <a href="{{ url('/product/'.$product->slug) }}" class="fp-wl-link" style="display:block;text-decoration:none;">
                    <div class="fp-wl-img-wrap">
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}" alt="{{ $product->name }}" loading="lazy" decoding="async">
                        @else
                            <div class="fp-wl-no-img"><i class="bi bi-image"></i></div>
                        @endif
                        @if($product->discount_percent)
                        <div class="fp-wl-badge">-{{ $product->discount_percent }}%</div>
                        @endif
                        <div class="fp-wl-overlay">
                            <span class="fp-wl-view-btn"><i class="bi bi-eye-fill"></i> Quick View</span>
                        </div>
                    </div>
                    <div class="fp-wl-body">
                        @if($product->category)
                        <div class="fp-wl-category">{{ $product->category->name }}</div>
                        @endif
                        <div class="fp-wl-name">{{ Str::limit($product->name, 50) }}</div>
                        <div class="fp-wl-price-row">
                            <span class="fp-wl-price">₦{{ number_format($product->price, 0) }}</span>
                            @if($product->old_price)
                            <span class="fp-wl-price-old">₦{{ number_format($product->old_price, 0) }}</span>
                            @endif
                        </div>
                        @if($product->installment_price)
                        <div class="fp-wl-installment">From <strong>₦{{ number_format($product->installment_price, 0) }}/mo</strong></div>
                        @endif
                    </div>
                </a>
                <div class="fp-wl-footer">
                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="fp-wl-remove-btn">
                            <i class="bi bi-heartbreak-fill"></i> Remove
                        </button>
                    </form>
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="fp-wl-cart-btn"><i class="bi bi-cart-plus-fill"></i> Add to Cart</button>
                    </form>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @else
        <div class="fp-wl-empty reveal-up">
            <div class="fp-wl-empty-icon"><i class="bi bi-heartbreak-fill"></i></div>
            <h3>Your Wishlist is Empty</h3>
            <p>Save items you love to your wishlist and come back to them anytime!</p>
            <a href="{{ url('/shop') }}" class="btn-primary-gold" style="display:inline-flex;"><i class="bi bi-grid-fill"></i> Browse Products</a>
        </div>
        @endif
    </div>
</section>

@include('frontend.partials.footer')
@endsection
