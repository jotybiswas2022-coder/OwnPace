@extends('frontend.app')
@section('title', 'Shopping Cart — OwnPace Store')

@section('content')
<section class="fp-cart-section">
    <div class="fp-cart-bg">
        <div class="fp-cart-grid"></div>
        <div class="fp-cart-orb c1"></div>
        <div class="fp-cart-orb c2"></div>
    </div>
    <div class="container position-relative">
        <div class="section-head">
            <div class="section-badge reveal-up"><i class="bi bi-cart-fill"></i> Shopping Cart</div>
            <h2 class="reveal-up">Your Cart</h2>
        </div>

        @if(session('success'))
        <div class="fp-cart-alert reveal-up">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(isset($cart) && count($cart) > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="fp-cart-header reveal-up">
                    <span><i class="bi bi-box-seam-fill"></i> {{ count($cart) }} {{ Str::plural('item', count($cart)) }}</span>
                </div>
                <div class="fp-cart-items">
                    @php $total = 0; @endphp
                    @foreach($cart as $item)
                    @php
                        $subtotal = $item['price'] * ($item['quantity'] ?? 1);
                        $total += $subtotal;
                    @endphp
                    <div class="fp-cart-item reveal-up" data-item-id="{{ $item['id'] }}">
                        <a href="{{ url('/product/'.$item['slug']) }}" class="fp-ci-image">
                            @if($item['thumbnail'])
                                <img src="{{ asset('storage/'.$item['thumbnail']) }}" alt="{{ $item['name'] }}" loading="lazy">
                            @else
                                <div class="fp-ci-no-img"><i class="bi bi-image"></i></div>
                            @endif
                        </a>
                        <div class="fp-ci-info">
                            <a href="{{ url('/product/'.$item['slug']) }}" class="fp-ci-name">{{ $item['name'] }}</a>
                            <div class="fp-ci-price">₦{{ number_format($item['price'], 0) }}</div>
                            <div class="fp-ci-mobile-total d-sm-none">Subtotal: ₦{{ number_format($subtotal, 0) }}</div>
                        </div>
                        <div class="fp-ci-qty">
                            <div class="fp-qty-control" data-product-id="{{ $item['id'] }}">
                                <button type="button" class="fp-qty-minus" data-action="decrease" aria-label="Decrease quantity"><i class="bi bi-dash"></i></button>
                                <input type="number" name="quantity" value="{{ $item['quantity'] ?? 1 }}" min="1" max="99" class="fp-qty-input" readonly aria-label="Quantity">
                                <button type="button" class="fp-qty-plus" data-action="increase" aria-label="Increase quantity"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                        <div class="fp-ci-total" id="item-total-{{ $item['id'] }}">₦{{ number_format($subtotal, 0) }}</div>
                        <a href="{{ route('cart.remove', $item['id']) }}" class="fp-ci-remove" title="Remove item" aria-label="Remove {{ $item['name'] }} from cart"><i class="bi bi-trash-fill"></i></a>
                    </div>
                    @endforeach
                </div>
                <div class="fp-cart-footer reveal-up">
                    <a href="{{ route('cart.clear') }}" class="fp-cart-clear" onclick="return confirm('Clear all items from your cart?')">
                        <i class="bi bi-trash-fill"></i> Clear Cart
                    </a>
                    <a href="{{ url('/shop') }}" class="fp-cart-shop">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="fp-cart-summary reveal-up">
                    <div class="fp-cs-icon"><i class="bi bi-receipt"></i></div>
                    <h4 class="fp-cs-title">Order Summary</h4>

                    <div class="fp-cs-body">
                        <div class="fp-cs-row">
                            <span>Subtotal <small>({{ count($cart) }} items)</small></span>
                            <span class="fp-cs-amount" id="cart-subtotal">₦{{ number_format($total, 0) }}</span>
                        </div>
                        <div class="fp-cs-row">
                            <span>Shipping</span>
                            <span class="fp-cs-shipping">Calculated at checkout</span>
                        </div>
                        <div class="fp-cs-divider"></div>
                        <div class="fp-cs-row fp-cs-total">
                            <span>Estimated Total</span>
                            <span class="fp-cs-amount fp-cs-total-amount" id="cart-total">₦{{ number_format($total, 0) }}</span>
                        </div>
                    </div>

                    <div class="fp-cs-promo">
                        <div class="fp-cs-promo-header">
                            <i class="bi bi-ticket-perforated-fill"></i>
                            <span>Have a promo code?</span>
                        </div>
                        <div class="fp-cs-promo-form">
                            <input type="text" placeholder="Enter code" class="fp-cs-promo-input">
                            <button class="fp-cs-promo-btn">Apply</button>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="fp-cart-checkout-btn">
                        <i class="bi bi-credit-card-fill"></i> Proceed to Checkout
                    </a>

                    <div class="fp-cart-trust">
                        <span><i class="bi bi-shield-fill-check"></i> Secure checkout</span>
                        <span><i class="bi bi-lock-fill"></i> Encrypted payment</span>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="fp-cart-empty reveal-up">
            <div class="fp-cart-empty-icon">
                <i class="bi bi-cart-x"></i>
            </div>
            <h3>Your Cart is Empty</h3>
            <p>Looks like you haven't added anything to your cart yet.</p>
            <div class="fp-cart-empty-actions">
                <a href="{{ url('/shop') }}" class="fp-cart-empty-btn">
                    <i class="bi bi-grid-fill"></i> Start Shopping
                </a>
                <a href="{{ url('/') }}" class="fp-cart-empty-link">
                    <i class="bi bi-house-door-fill"></i> Go Home
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

@include('frontend.partials.footer')

<style>
/* ===== CART SECTION ===== */
.fp-cart-section {
    background: linear-gradient(135deg, #0A0A0B 0%, #121214 50%, #0A0A0B 100%);
    padding: 50px 0 80px;
    min-height: 100vh;
    position: relative; overflow: hidden;
}
.fp-cart-bg { position: absolute; inset: 0; pointer-events: none; z-index: 0; }
.fp-cart-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-cart-orb {
    position: absolute; border-radius: 50%; filter: blur(100px);
    animation: cartOrb 10s ease-in-out infinite alternate;
}
.c1 { width: 350px; height: 350px; background: rgba(234,179,8,0.04); top: -100px; left: 5%; }
.c2 { width: 250px; height: 250px; background: rgba(234,179,8,0.03); bottom: -80px; right: 20%; animation-delay: 5s; }
@keyframes cartOrb { 0%{transform:translate(0)scale(1)} 100%{transform:translate(25px,20px)scale(1.12)} }

/* ===== ALERT ===== */
.fp-cart-alert {
    display: flex; align-items: center; gap: 10px;
    background: rgba(34,197,94,0.08);
    border: 1px solid rgba(34,197,94,0.2);
    color: #4ade80;
    padding: 14px 20px; border-radius: 10px;
    font-weight: 500; font-size: 14px;
    margin-bottom: 24px; max-width: 800px;
    animation: alertSlide 0.4s ease-out;
}
@keyframes alertSlide {
    from { opacity:0; transform: translateY(-10px); }
    to { opacity:1; transform: translateY(0); }
}

/* ===== HEADER ===== */
.fp-cart-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px; padding: 0 4px;
}
.fp-cart-header span {
    font-size: 14px; color: var(--text-muted);
    display: flex; align-items: center; gap: 6px;
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    padding: 8px 18px; border-radius: 8px;
}
.fp-cart-header span i { color: var(--gold-500); }

/* ===== ITEMS ===== */
.fp-cart-items { display: flex; flex-direction: column; gap: 10px; }
.fp-cart-item {
    display: flex; align-items: center; gap: 16px;
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 16px 20px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
}
.fp-cart-item::after {
    content: ''; position: absolute; inset: 0;
    border-radius: 12px;
    pointer-events: none; opacity: 0;
    transition: opacity 0.4s;
    box-shadow: inset 0 0 0 1px rgba(234,179,8,0.15);
}
.fp-cart-item:hover::after { opacity: 1; }
.fp-cart-item:hover {
    border-color: rgba(234,179,8,0.3);
    box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    transform: translateX(4px);
}
.fp-ci-image {
    width: 88px; height: 88px; border-radius: 10px;
    background: var(--dark-900); overflow: hidden; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid var(--card-border);
    transition: all 0.3s;
}
.fp-ci-image:hover { border-color: var(--gold-500); transform: scale(1.04); }
.fp-ci-image img { width: 100%; height: 100%; object-fit: cover; }
.fp-ci-no-img { color: var(--card-border); font-size: 24px; }
.fp-ci-info { flex: 1; min-width: 0; overflow-wrap: break-word; }
.fp-ci-name {
    color: var(--text-primary); font-weight: 600; font-size: 14px;
    display: block; margin-bottom: 4px;
    text-decoration: none; transition: color 0.2s;
}
.fp-ci-name:hover { color: var(--gold-400); }
.fp-ci-price { color: var(--gold-400); font-weight: 700; font-size: 14px; }
.fp-ci-mobile-total { font-size: 12px; color: var(--text-dim); margin-top: 4px; }

/* ===== QUANTITY ===== */
.fp-ci-qty { flex-shrink: 0; }
.fp-qty-control {
    display: flex; align-items: center;
    background: var(--surface-dark);
    border: 1px solid var(--card-border);
    border-radius: 8px;
    overflow: hidden;
    transition: border-color 0.3s;
}
.fp-qty-control:hover { border-color: rgba(234,179,8,0.2); }
.fp-qty-minus, .fp-qty-plus {
    width: 36px; height: 36px; border: none;
    background: transparent; color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 14px;
    transition: all 0.25s ease; font-family: inherit;
    touch-action: manipulation;
}
.fp-qty-minus:hover, .fp-qty-plus:hover {
    background: rgba(234,179,8,0.1); color: var(--gold-400);
}
.fp-qty-minus:active, .fp-qty-plus:active {
    background: rgba(234,179,8,0.15);
    transform: scale(0.93);
}
.fp-qty-input {
    width: 42px; padding: 0; border: none;
    background: transparent; color: var(--text-primary);
    font-size: 14px; font-weight: 600; text-align: center;
    font-family: inherit; outline: none;
    -moz-appearance: textfield;
}
.fp-qty-input::-webkit-inner-spin-button,
.fp-qty-input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

.fp-ci-total {
    font-weight: 700; color: var(--text-primary);
    font-size: 16px; min-width: 90px; text-align: right;
    font-family: 'Syne', sans-serif;
}
.fp-ci-remove {
    color: var(--text-dim); font-size: 18px;
    transition: all 0.25s ease; padding: 8px;
    border-radius: 8px; display: flex;
    text-decoration: none;
}
.fp-ci-remove:hover {
    color: #ef4444;
    background: rgba(239,68,68,0.08);
    transform: scale(1.1);
}

/* ===== CART FOOTER ===== */
.fp-cart-footer {
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 16px; padding: 0 4px; flex-wrap: wrap; gap: 8px;
}
.fp-cart-clear {
    display: inline-flex; align-items: center; gap: 6px;
    color: var(--text-dim); font-size: 13px; font-weight: 500;
    padding: 9px 16px; border: 1px solid var(--card-border);
    border-radius: 8px; transition: all 0.3s; text-decoration: none;
}
.fp-cart-clear:hover {
    border-color: rgba(239,68,68,0.3);
    color: #ef4444;
    background: rgba(239,68,68,0.04);
}
.fp-cart-shop {
    display: inline-flex; align-items: center; gap: 6px;
    color: var(--text-muted); font-size: 13px; font-weight: 500;
    transition: all 0.3s; text-decoration: none;
    padding: 9px 16px;
    border-radius: 8px;
}
.fp-cart-shop:hover {
    color: var(--gold-400);
    background: rgba(234,179,8,0.04);
}

/* ===== ORDER SUMMARY ===== */
.fp-cart-summary {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    padding: 28px 24px;
    position: sticky; top: 100px;
    transition: all 0.3s;
}
.fp-cart-summary:hover {
    border-color: rgba(234,179,8,0.12);
    box-shadow: var(--shadow-glow-sm);
}
.fp-cs-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: rgba(234,179,8,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-500); font-size: 22px;
    margin-bottom: 16px;
}
.fp-cs-title {
    font-family: 'Syne', sans-serif;
    font-size: 18px; font-weight: 700;
    color: var(--text-primary); margin-bottom: 20px;
}
.fp-cs-body { margin-bottom: 20px; }
.fp-cs-row {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 12px; font-size: 14px; color: var(--text-muted);
}
.fp-cs-row small { color: var(--text-dim); font-size: 12px; }
.fp-cs-amount { font-weight: 600; color: var(--text-primary); }
.fp-cs-shipping { color: var(--gold-400); font-size: 12px; font-weight: 500; }
.fp-cs-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--card-border), transparent);
    margin: 16px 0;
}
.fp-cs-total {
    font-size: 18px; font-weight: 700;
    color: var(--text-primary); margin-bottom: 0;
}
.fp-cs-total-amount {
    color: var(--gold-400); font-size: 20px;
    font-family: 'Syne', sans-serif;
}

/* ===== PROMO ===== */
.fp-cs-promo {
    background: var(--surface-dark);
    border: 1px solid var(--card-border);
    border-radius: 10px;
    padding: 16px; margin-bottom: 20px;
    transition: border-color 0.3s;
}
.fp-cs-promo:hover {
    border-color: rgba(234,179,8,0.12);
}
.fp-cs-promo-header {
    display: flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: var(--text-muted);
    margin-bottom: 10px;
}
.fp-cs-promo-header i { color: var(--gold-500); font-size: 14px; }
.fp-cs-promo-form {
    display: flex; gap: 8px;
}
.fp-cs-promo-input {
    flex: 1; padding: 10px 14px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: 8px; color: var(--text-primary);
    font-size: 13px; outline: none; font-family: inherit;
    transition: all 0.25s ease;
}
.fp-cs-promo-input:focus {
    border-color: var(--gold-500);
    box-shadow: 0 0 0 3px rgba(234,179,8,0.08);
}
.fp-cs-promo-btn {
    padding: 10px 18px; border: none;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); border-radius: 8px;
    font-weight: 700; font-size: 12px; cursor: pointer;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family: inherit; white-space: nowrap;
}
.fp-cs-promo-btn:hover {
    transform: scale(1.04);
    box-shadow: 0 4px 12px rgba(234,179,8,0.2);
}

/* ===== CHECKOUT BUTTON ===== */
.fp-cart-checkout-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 15px; margin-bottom: 14px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); border-radius: 10px;
    font-weight: 700; font-size: 15px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none; position: relative; overflow: hidden;
}
.fp-cart-checkout-btn::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.15) 50%, transparent 80%);
    transform: translateX(-100%); transition: transform 0.6s;
}
.fp-cart-checkout-btn:hover::before { transform: translateX(100%); }
.fp-cart-checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(234,179,8,0.25);
    color: var(--near-black);
}

.fp-cart-trust {
    display: flex; gap: 20px; justify-content: center;
}
.fp-cart-trust span {
    display: flex; align-items: center; gap: 5px;
    font-size: 12px; color: var(--text-dim);
    transition: color 0.3s;
}
.fp-cart-trust span:hover { color: var(--text-muted); }
.fp-cart-trust i { color: var(--gold-500); font-size: 11px; }

/* ===== EMPTY ===== */
.fp-cart-empty {
    text-align: center; padding: 80px 20px;
    max-width: 520px; margin: 0 auto;
}
.fp-cart-empty-icon {
    width: 100px; height: 100px; border-radius: 24px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 24px; font-size: 42px; color: var(--text-dim);
    transition: all 0.3s;
}
.fp-cart-empty:hover .fp-cart-empty-icon {
    border-color: rgba(234,179,8,0.2);
    transform: scale(1.05);
}
.fp-cart-empty h3 {
    font-family: 'Syne', sans-serif;
    font-size: 24px; font-weight: 700;
    color: var(--text-primary); margin-bottom: 8px;
}
.fp-cart-empty p { color: var(--text-muted); font-size: 15px; margin-bottom: 28px; }
.fp-cart-empty-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
.fp-cart-empty-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black);
    padding: 14px 30px; border-radius: 10px;
    font-weight: 700; font-size: 14px; text-decoration: none;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-cart-empty-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-gold-lg);
    color: var(--near-black);
}
.fp-cart-empty-link {
    display: inline-flex; align-items: center; gap: 8px;
    color: var(--text-muted); border: 1px solid var(--card-border);
    padding: 14px 30px; border-radius: 10px;
    font-weight: 600; font-size: 14px; text-decoration: none;
    transition: all 0.3s;
}
.fp-cart-empty-link:hover { border-color: var(--gold-400); color: var(--gold-400); background: rgba(234,179,8,0.04); }

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .fp-cart-summary { position: static; margin-top: 24px; }
}
@media (max-width: 768px) {
    .fp-cart-section { padding: 30px 0 60px; }
}
@media (max-width: 576px) {
    .fp-cart-item {
        flex-wrap: wrap; padding: 12px 14px; gap: 10px;
    }
    .fp-ci-image { width: 72px; height: 72px; }
    .fp-ci-total { display: none; }
    .fp-ci-info { flex: 1 1 calc(100% - 120px); }
    .fp-ci-qty { order: 3; }
    .fp-cart-empty-icon { width: 80px; height: 80px; font-size: 32px; }
    .fp-cart-empty { padding: 60px 20px; }
}
</style>

<script>
document.querySelectorAll('.fp-qty-control').forEach(control => {
    const input = control.querySelector('.fp-qty-input');
    const minus = control.querySelector('.fp-qty-minus');
    const plus = control.querySelector('.fp-qty-plus');
    const productId = control.dataset.productId;
    const totalEl = document.getElementById('item-total-' + productId);

    function updateCart(qty) {
        input.value = qty;
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: qty })
        }).then(r => r.json()).then(data => {
            if (data.success) { location.reload(); }
        });
    }

    minus.addEventListener('click', () => {
        let val = parseInt(input.value) || 1;
        if (val > 1) updateCart(val - 1);
    });

    plus.addEventListener('click', () => {
        let val = parseInt(input.value) || 1;
        if (val < 99) updateCart(val + 1);
    });
});
</script>
@endsection