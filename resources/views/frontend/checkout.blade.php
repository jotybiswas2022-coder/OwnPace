@extends('frontend.app')
@section('title', 'Checkout — OwnPace Store')

@section('content')
<style>
.fp-chk-hero {
    position: relative; padding: 40px 0 60px; overflow: hidden;
    background: linear-gradient(180deg, rgba(234,179,8,0.04) 0%, transparent 100%);
}
.fp-chk-orb {
    position: absolute; width: 500px; height: 500px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.06) 0%, transparent 60%);
    top: -200px; right: -100px; pointer-events: none;
    animation: chkOrbPulse 4s ease-in-out infinite;
}
.fp-chk-orb2 {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    bottom: -150px; left: -100px; pointer-events: none;
    animation: chkOrbPulse 5s ease-in-out infinite reverse;
}
@keyframes chkOrbPulse { 0%,100%{transform:scale(1);opacity:0.5} 50%{transform:scale(1.1);opacity:1} }

.fp-chk-steps {
    display: flex; align-items: center; justify-content: center; gap: 0;
    margin-bottom: 40px; position: relative; z-index: 1;
}
.fp-chk-step {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px; font-size: 13px; font-weight: 600;
    color: var(--text-dim); position: relative;
    touch-action: manipulation;
}
.fp-chk-step .step-num {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
    background: var(--card-dark); border: 2px solid var(--card-border);
    color: var(--text-dim); transition: all 0.3s;
}
.fp-chk-step.active { color: var(--gold-400); }
.fp-chk-step.active .step-num {
    background: var(--gold-500); border-color: var(--gold-500);
    color: var(--near-black); box-shadow: 0 0 20px rgba(234,179,8,0.3);
}
.fp-chk-step.done { color: var(--text-muted); }
.fp-chk-step.done .step-num {
    background: rgba(234,179,8,0.15); border-color: var(--gold-500);
    color: var(--gold-500);
}
.fp-chk-step-line {
    width: 40px; height: 2px; background: var(--card-border);
}
.fp-chk-step-line.done { background: var(--gold-500); }

.fp-chk-card {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    padding: 28px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative; overflow: hidden;
    transform: translateZ(0);
    contain: layout style;
}
.fp-chk-card:hover {
    border-color: rgba(234,179,8,0.15);
    box-shadow: var(--shadow-card-hover);
}
.fp-chk-card-title {
    font-family: 'Syne', sans-serif;
    color: var(--text-primary); font-size: 15px;
    margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
}
.fp-chk-card-title i { color: var(--gold-500); font-size: 18px; }

.fp-chk-radio-card {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 16px; border: 2px solid var(--card-border);
    border-radius: var(--radius-sm); cursor: pointer;
    transition: all 0.3s; position: relative;
    background: var(--surface-dark); touch-action: manipulation;
    transform: translateZ(0);
}
.fp-chk-radio-card:hover {
    border-color: rgba(234,179,8,0.3);
    background: rgba(234,179,8,0.02);
}
.fp-chk-radio-card.active {
    border-color: var(--gold-500);
    background: rgba(234,179,8,0.05);
    box-shadow: 0 0 20px rgba(234,179,8,0.08);
}
.fp-chk-radio-card input[type="radio"] {
    position: absolute; opacity: 0; pointer-events: none;
}
.fp-chk-radio-dot {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px; transition: all 0.3s;
}
.fp-chk-radio-card.active .fp-chk-radio-dot {
    border-color: var(--gold-500);
    background: var(--gold-500);
}
.fp-chk-radio-dot::after {
    content: ''; width: 8px; height: 8px; border-radius: 50%;
    background: var(--near-black); transform: scale(0);
    transition: transform 0.3s;
}
.fp-chk-radio-card.active .fp-chk-radio-dot::after { transform: scale(1); }

.fp-chk-pm-card {
    flex: 1; padding: 20px; border: 2px solid var(--card-border);
    border-radius: var(--radius-sm); text-align: center; cursor: pointer;
    transition: all 0.3s; background: var(--surface-dark);
    position: relative;
}
.fp-chk-pm-card:hover {
    border-color: rgba(234,179,8,0.3);
    transform: translateY(-2px);
}
.fp-chk-pm-card.active {
    border-color: var(--gold-500);
    background: rgba(234,179,8,0.05);
    box-shadow: 0 0 20px rgba(234,179,8,0.08);
    transform: translateY(-2px);
}
.fp-chk-pm-card input[type="radio"] {
    position: absolute; opacity: 0; pointer-events: none;
}
.fp-chk-pm-card i {
    font-size: 28px; color: var(--gold-500); display: block; margin-bottom: 8px;
}
.fp-chk-pm-card strong {
    color: var(--text-primary); font-size: 14px; display: block;
}
.fp-chk-pm-card small {
    color: var(--text-dim); display: block; margin-top: 4px; font-size: 12px;
}

.fp-chk-checkbox {
    display: flex; align-items: center; gap: 10px; cursor: pointer;
}
.fp-chk-checkbox input[type="checkbox"] {
    width: 20px; height: 20px; accent-color: var(--gold-500);
    cursor: pointer; flex-shrink: 0;
}

.fp-chk-select {
    width: 100%; padding: 12px 14px;
    background: var(--surface-dark); border: 2px solid var(--card-border);
    border-radius: var(--radius-sm); color: var(--text-primary);
    font-size: 14px; font-family: inherit; cursor: pointer;
    transition: border-color 0.3s;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23A1A1AA' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center;
    padding-right: 36px;
}
.fp-chk-select:focus { outline: none; border-color: var(--gold-500); box-shadow: 0 0 0 3px rgba(234,179,8,0.1); }
.fp-chk-select option { background: var(--near-black); color: var(--text-primary); }

.fp-chk-summary-card {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    padding: 24px;
    transition: border-color 0.3s;
    position: relative; transform: translateZ(0);
    overflow-wrap: break-word;
}
.fp-chk-summary-card:hover {
    border-color: rgba(234,179,8,0.15);
    box-shadow: var(--shadow-card-hover);
}
.fp-chk-summary-title {
    font-family: 'Syne', sans-serif;
    color: var(--text-primary); font-size: 16px;
    margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;
}
.fp-chk-summary-title span {
    font-size: 12px; color: var(--text-dim); font-weight: 400;
}

.fp-chk-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 0; border-bottom: 1px solid var(--card-border);
}
.fp-chk-item:last-child { border-bottom: none; }
.fp-chk-item-img {
    width: 52px; height: 52px; border-radius: 8px; object-fit: cover;
    background: var(--surface-dark); flex-shrink: 0;
}
.fp-chk-item-img-placeholder {
    width: 52px; height: 52px; border-radius: 8px;
    background: var(--surface-dark); display: flex; align-items: center;
    justify-content: center; color: var(--card-border); font-size: 18px;
    flex-shrink: 0;
}
.fp-chk-item-info { flex: 1; min-width: 0; }
.fp-chk-item-name {
    color: var(--text-primary); font-size: 13px; font-weight: 600;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.fp-chk-item-qty { color: var(--text-dim); font-size: 12px; margin-top: 2px; }
.fp-chk-item-price { color: var(--gold-400); font-weight: 700; font-size: 14px; white-space: nowrap; }

.fp-chk-total-line {
    display: flex; justify-content: space-between;
    font-size: 14px; color: var(--text-muted); margin-bottom: 10px;
}
.fp-chk-total-line.final {
    font-size: 20px; font-weight: 700; color: var(--text-primary);
    margin-top: 16px; padding-top: 16px;
    border-top: 2px solid var(--card-border);
}
.fp-chk-total-line.final span:last-child { color: var(--gold-400); }

.fp-chk-place-btn {
    width: 100%; margin-top: 24px;
    display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); padding: 16px 28px; border-radius: var(--radius-sm);
    font-weight: 700; font-size: 15px; border: none; cursor: pointer;
    transition: all 0.3s; font-family: inherit; position: relative; overflow: hidden;
}
.fp-chk-place-btn::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.15) 50%, transparent 80%);
    transform: translateX(-100%); transition: transform 0.6s;
}
.fp-chk-place-btn:hover::before { transform: translateX(100%); }
.fp-chk-place-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(234,179,8,0.25);
    color: var(--near-black);
}

.fp-chk-trust {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;
    margin-top: 20px;
}
.fp-chk-trust-item {
    display: flex; align-items: center; gap: 8px;
    padding: 10px; border-radius: var(--radius-sm);
    background: var(--surface-dark); border: 1px solid var(--card-border);
    font-size: 11px; color: var(--text-dim); font-weight: 500;
}
.fp-chk-trust-item i { color: var(--gold-500); font-size: 14px; }

.fp-chk-promo { margin-top:16px; }
.fp-chk-promo-form { display:flex; gap:8px; }
.fp-chk-promo-applied { display:flex; align-items:center; gap:10px; padding:10px 14px; background:rgba(74,222,128,0.05); border:1px solid rgba(74,222,128,0.2); border-radius:8px; font-size:13px; }
.fp-chk-promo-applied span { display:flex; align-items:center; gap:6px; }

.fp-chk-error {
    display: flex; align-items: center; gap: 8px;
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.2);
    color: #ef4444; padding: 14px 18px;
    border-radius: var(--radius-sm); font-weight: 500; font-size: 13px;
    margin-bottom: 24px; animation: chkShake 0.4s ease-out;
}
@keyframes chkShake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-6px)} 50%{transform:translateX(6px)} 75%{transform:translateX(-3px)} }

.fp-chk-field-error {
    color: #ef4444; font-size: 12px; margin-top: 6px; display: block;
}

@media (max-width: 768px) {
    .fp-chk-steps { gap: 4px; }
    .fp-chk-step { padding: 8px 10px; font-size: 11px; }
    .fp-chk-step .step-num { width: 26px; height: 26px; font-size: 11px; }
    .fp-chk-step-line { width: 20px; }
    .fp-chk-card { padding: 20px; }
    .fp-chk-pm-card { padding: 14px; }
    .fp-chk-trust { grid-template-columns: 1fr; }
}
</style>
<section class="fp-chk-hero">
    <div class="fp-chk-orb"></div>
    <div class="fp-chk-orb2"></div>
    <div class="container">
        <div class="section-head reveal-up">
            <div class="section-badge"><i class="bi bi-credit-card-fill"></i> Secure Checkout</div>
            <h2>Complete Your Purchase</h2>
            <p>You're just a few steps away from owning your items</p>
        </div>

        <div class="fp-chk-steps reveal-up">
            <div class="fp-chk-step active" aria-current="step">
                <span class="step-num">1</span>
                <span>Delivery</span>
            </div>
            <div class="fp-chk-step-line"></div>
            <div class="fp-chk-step">
                <span class="step-num">2</span>
                <span>Payment</span>
            </div>
            <div class="fp-chk-step-line"></div>
            <div class="fp-chk-step">
                <span class="step-num">3</span>
                <span>Confirm</span>
            </div>
        </div>
    </div>
</section>

<section style="padding-bottom:80px;">
    <div class="container">
        @if(session('error'))
        <div class="fp-chk-error reveal-up">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm" novalidate>
            @csrf
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="fp-chk-card reveal-left" style="transition-delay:0.1s;">
                        <h5 class="fp-chk-card-title"><i class="bi bi-geo-alt-fill"></i> Delivery Address</h5>
                        @if($addresses && $addresses->count() > 0)
                            @foreach($addresses as $addr)
                            <div class="fp-chk-radio-card mb-2 addr-card {{ old('delivery_address_id') == $addr->id ? 'active' : '' }}" onclick="selectAddr(this, {{ $addr->id }})">
                                <input type="radio" name="delivery_address_id" value="{{ $addr->id }}" {{ old('delivery_address_id') == $addr->id ? 'checked' : '' }} required>
                                <div class="fp-chk-radio-dot"></div>
                                <div style="flex:1;">
                                    <strong style="color:var(--text-primary);display:block;font-size:14px;">{{ $addr->label ?? 'Address' }}</strong>
                                    <span style="color:var(--text-muted);font-size:13px;">{{ $addr->address_line1 }}, {{ $addr->city }}, {{ $addr->state }}</span>
                                    @if($addr->phone)
                                    <span style="color:var(--text-dim);font-size:12px;display:block;margin-top:4px;"><i class="bi bi-telephone-fill" style="font-size:11px;"></i> {{ $addr->phone }}</span>
                                    @endif
                                </div>
                                @if($addr->is_default)
                                <span style="font-size:10px;font-weight:700;color:var(--gold-500);background:rgba(234,179,8,0.1);padding:3px 10px;border-radius:99px;white-space:nowrap;">Default</span>
                                @endif
                            </div>
                            @endforeach
                        @else
                            <div style="text-align:center;padding:20px;">
                                <i class="bi bi-geo-alt" style="font-size:40px;color:var(--card-border);display:block;margin-bottom:12px;"></i>
                                <p style="color:var(--text-dim);font-size:14px;margin-bottom:12px;">No saved addresses found.</p>
                                <a href="{{ route('profile.addresses') }}" class="btn-primary-gold" style="display:inline-flex;"><i class="bi bi-plus-lg"></i> Add Address</a>
                            </div>
                        @endif
                        @error('delivery_address_id')<small class="fp-chk-field-error">{{ $message }}</small>@enderror

                        <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--card-border);">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#addCheckoutAddress" class="fp-btn fp-btn-ghost" style="font-size:12px;">
                                <i class="bi bi-plus-lg"></i> Add a new address
                            </a>
                        </div>

                        {{-- ===== DELIVERY PROXY ===== --}}
                        <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--card-border);">
                            <h5 class="fp-chk-card-title" style="margin-bottom:8px;"><i class="bi bi-person-check"></i> Receive for Someone Else?</h5>
                            <p style="color:var(--text-dim);font-size:12px;margin-bottom:10px;">
                                Assign another store member to receive this delivery on your behalf.
                            </p>
                            <input type="hidden" name="delivery_proxy_user_id" id="deliveryProxyUserId" value="{{ old('delivery_proxy_user_id') }}">
                            <div class="fp-partial-form" style="display:flex;gap:8px;">
                                <input type="text" id="proxySearchInput" placeholder="Search by phone, email or name"
                                       style="flex:1;background:var(--surface-dark);border:1.5px solid var(--card-border);color:var(--text-primary);padding:10px 12px;border-radius:var(--radius-sm);font-size:13px;outline:none;min-width:0;">
                                <button type="button" class="fp-action-btn outline" style="padding:10px 16px;font-size:12px;" id="proxySearchBtn">
                                    <i class="bi bi-search"></i> Find
                                </button>
                            </div>
                            <div id="proxyResults" style="margin-top:10px;"></div>
                            <div id="proxyAssigned" style="margin-top:10px;display:none;">
                                <div class="fp-chk-promo-applied">
                                    <span><i class="bi bi-person-check-fill"></i> <strong id="proxyAssignedName"></strong> will receive your delivery</span>
                                    <a href="#" id="proxyClear" style="color:#ef4444;font-size:11px;text-decoration:none;">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="fp-chk-card reveal-left" style="transition-delay:0.2s;">
                        <h5 class="fp-chk-card-title"><i class="bi bi-coin"></i> Payment Method</h5>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:16px;">
                            @php $pmType = old('payment_type', 'full'); @endphp
                            <div class="fp-chk-pm-card {{ $pmType == 'full' ? 'active' : '' }}" onclick="selectPM(this, 'full')">
                                <input type="radio" name="payment_type" value="full" {{ $pmType == 'full' ? 'checked' : '' }} required>
                                <i class="bi bi-cash-stack"></i>
                                <strong>Pay in Full</strong>
                                <small>One-time payment</small>
                            </div>
                            <div class="fp-chk-pm-card {{ $pmType == 'installment' ? 'active' : '' }}" onclick="selectPM(this, 'installment')">
                                <input type="radio" name="payment_type" value="installment" {{ $pmType == 'installment' ? 'checked' : '' }} required>
                                <i class="bi bi-calendar-check"></i>
                                <strong>Pay in Installments</strong>
                                <small>Flexible plans</small>
                            </div>
                        </div>
                        @error('payment_type')<small class="fp-chk-field-error">{{ $message }}</small>@enderror

                        <div id="planSelect" style="display:{{ $pmType == 'installment' ? 'block' : 'none' }};">
                            <livewire:checkout-breakdown
                                :subtotal="(float) max($total - $discount, 0)"
                                :shipping-fee="(float) $shippingFee"
                                wire:key="checkout-breakdown"
                            />
                        </div>
                    </div>

                    <div class="fp-chk-card reveal-left" style="transition-delay:0.3s;">
                        <h5 class="fp-chk-card-title"><i class="bi bi-shield-fill-check"></i> Extras</h5>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <i class="bi bi-shield-check" style="color:var(--text-dim);font-size:20px;"></i>
                            <span style="color:var(--text-dim);font-size:14px;">Add insurance in the payment step above to protect your purchase.</span>
                        </div>
                    </div>

                    <div class="fp-chk-card reveal-left" style="transition-delay:0.4s;">
                        <h5 class="fp-chk-card-title"><i class="bi bi-credit-card-2-front"></i> Pay Using</h5>
                        @php $pmMethod = old('payment_method', 'paystack'); @endphp
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            <div class="fp-chk-pm-card {{ $pmMethod == 'wallet' ? 'active' : '' }}" onclick="selectGW(this, 'wallet')" style="flex:1;min-width:120px;">
                                <input type="radio" name="payment_method" value="wallet" {{ $pmMethod == 'wallet' ? 'checked' : '' }} required>
                                <i class="bi bi-wallet2"></i>
                                <strong>Wallet</strong>
                                <small>₦{{ number_format($wallet->balance ?? 0, 0) }}</small>
                            </div>
                            <div class="fp-chk-pm-card {{ $pmMethod == 'paystack' ? 'active' : '' }}" onclick="selectGW(this, 'paystack')" style="flex:1;min-width:120px;">
                                <input type="radio" name="payment_method" value="paystack" {{ $pmMethod == 'paystack' ? 'checked' : '' }} required>
                                <i class="bi bi-credit-card-fill"></i>
                                <strong>Paystack</strong>
                                <small>Card, Bank, USSD</small>
                            </div>
                            <div class="fp-chk-pm-card {{ $pmMethod == 'flutterwave' ? 'active' : '' }}" onclick="selectGW(this, 'flutterwave')" style="flex:1;min-width:120px;">
                                <input type="radio" name="payment_method" value="flutterwave" {{ $pmMethod == 'flutterwave' ? 'checked' : '' }} required>
                                <i class="bi bi-globe"></i>
                                <strong>Flutterwave</strong>
                                <small>Card, Bank, Mobile Money</small>
                            </div>
                            <div class="fp-chk-pm-card {{ $pmMethod == 'korapay' ? 'active' : '' }}" onclick="selectGW(this, 'korapay')" style="flex:1;min-width:120px;">
                                <input type="radio" name="payment_method" value="korapay" {{ $pmMethod == 'korapay' ? 'checked' : '' }} required>
                                <i class="bi bi-shield-fill-check"></i>
                                <strong>Korapay</strong>
                                <small>Card, Bank Transfer, USSD</small>
                            </div>
                        </div>
                        @error('payment_method')<small class="fp-chk-field-error">{{ $message }}</small>@enderror
                    </div>

                    <div class="reveal-left" style="transition-delay:0.5s;">
                        <label class="fp-chk-checkbox" style="padding:8px 0;">
                            <input type="checkbox" name="agree_terms" value="1" required>
                            <span style="color:var(--text-muted);font-size:13px;">I agree to the <a href="{{ url('/terms') }}" target="_blank" style="color:var(--gold-500);text-decoration:underline;">Terms & Conditions</a> and <a href="{{ url('/terms/privacy') }}" target="_blank" style="color:var(--gold-500);text-decoration:underline;">Privacy Policy</a></span>
                        </label>
                        <div id="planTermsNote" style="display:none;margin:4px 0 0 30px;font-size:12px;color:var(--text-dim);">
                            <i class="bi bi-file-earmark-text-fill" style="color:var(--gold-500);font-size:11px;"></i>
                            Also bound by: <a id="planTermsLink" href="#" target="_blank" style="color:var(--gold-500);text-decoration:underline;"></a>
                        </div>
                        @error('agree_terms')<small class="fp-chk-field-error">{{ $message }}</small>@enderror
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="fp-chk-summary-card reveal-right" style="transition-delay:0.2s;">
                        <div class="fp-chk-summary-title">
                            Order Summary
                            <span>{{ count($cart) }} {{ count($cart) === 1 ? 'item' : 'items' }}</span>
                        </div>

                        <div style="max-height:320px;overflow-y:auto;">
                            @foreach($cart as $item)
                            <div class="fp-chk-item">
                                @if($item['thumbnail'])
                                <img src="{{ asset('storage/'.$item['thumbnail']) }}" alt="" class="fp-chk-item-img" loading="lazy" decoding="async">
                                @else
                                <div class="fp-chk-item-img-placeholder"><i class="bi bi-image"></i></div>
                                @endif
                                <div class="fp-chk-item-info">
                                    <div class="fp-chk-item-name">{{ $item['name'] }}</div>
                                    <div class="fp-chk-item-qty">Qty: {{ $item['quantity'] }}</div>
                                </div>
                                <div class="fp-chk-item-price">₦{{ number_format($item['price'] * $item['quantity'], 0) }}</div>
                            </div>
                            @endforeach
                        </div>

                        <div class="fp-chk-promo">
                            @if($promoCode ?? null)
                                <div class="fp-chk-promo-applied">
                                    <span><i class="bi bi-tag-fill"></i> {{ $promoCode->code }}</span>
                                    <span style="color:#4ade80;">-₦{{ number_format($discount, 0) }}</span>
                                    <a href="{{ route('checkout.remove-promo') }}" style="color:#ef4444;font-size:11px;text-decoration:none;">Remove</a>
                                </div>
                            @else
                                <form action="{{ route('checkout.apply-promo') }}" method="POST" class="fp-chk-promo-form">
                                    @csrf
                                    <input type="text" name="code" class="fp-chk-promo-input" placeholder="Promo code" style="flex:1;background:#121214;border:1px solid #2A2A2E;border-radius:6px;padding:8px 12px;color:#F4F4F5;font-size:13px;font-family:inherit;">
                                    <button type="submit" class="fp-chk-promo-btn" style="background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.2);border-radius:6px;padding:8px 14px;color:var(--gold-400);font-size:12px;font-weight:600;cursor:pointer;font-family:inherit;">Apply</button>
                                </form>
                            @endif
                        </div>

                        <div style="margin-top:16px;">
                            <div class="fp-chk-total-line">
                                <span>Subtotal</span>
                                <span>₦{{ number_format($total, 0) }}</span>
                            </div>
                            @if($discount > 0)
                            <div class="fp-chk-total-line">
                                <span>Discount</span>
                                <span style="color:#4ade80;">-₦{{ number_format($discount, 0) }}</span>
                            </div>
                            @endif
                            <div class="fp-chk-total-line">
                                <span>Delivery fee</span>
                                <span class="fp-mono" style="color:var(--gold-400);">₦{{ number_format((float) $shippingFee, 2) }}</span>
                            </div>
                            <div class="fp-chk-total-line final">
                                <span>Total</span>
                                <span class="fp-mono">₦{{ number_format(max($total - $discount + $shippingFee, 0), 0) }}</span>
                            </div>
                            <p style="margin-top:12px;padding:10px 12px;background:rgba(234,179,8,0.05);border:1px dashed rgba(234,179,8,0.25);border-radius:var(--radius-sm);font-size:11px;color:var(--text-dim);line-height:1.6;">
                                <i class="bi bi-truck-front-fill" style="color:var(--gold-500);"></i>
                                Your delivery fee is included here and covered within your first 70% of payments — it is never charged as a separate line later.
                            </p>
                        </div>

                        <button type="submit" class="fp-chk-place-btn" id="placeOrderBtn">
                            <i class="bi bi-shield-lock-fill"></i> Place Order
                        </button>

                        <div class="fp-chk-trust">
                            <div class="fp-chk-trust-item">
                                <i class="bi bi-lock-fill"></i> Secure SSL
                            </div>
                            <div class="fp-chk-trust-item">
                                <i class="bi bi-shield-fill-check"></i> Encrypted
                            </div>
                            <div class="fp-chk-trust-item">
                                <i class="bi bi-arrow-repeat"></i> Easy Returns
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@include('frontend.partials.footer')
@endsection

{{-- ===== ADD-ADDRESS MODAL (keeps checkout to one page) ===== --}}
<div class="modal fade" id="addCheckoutAddress" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius-lg);">
            <div class="modal-header" style="border-bottom:1px solid var(--card-border);padding:20px 24px;">
                <h5 class="modal-title" style="color:var(--text-primary);font-family:'Syne',sans-serif;font-size:16px;font-weight:700;"><i class="bi bi-geo-alt-fill" style="color:var(--gold-500);"></i> Add Delivery Address</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.addresses.store') }}">
                @csrf
                <div class="modal-body" style="padding:24px;">
                    <div class="row g-3">
                        <div class="col-6"><input type="text" name="recipient_name" class="fp-chk-select" style="padding:10px 12px;" placeholder="Recipient name" required></div>
                        <div class="col-6"><input type="text" name="label" class="fp-chk-select" style="padding:10px 12px;" placeholder="Label (Home, Office)"></div>
                        <div class="col-12"><input type="text" name="address_line1" class="fp-chk-select" style="padding:10px 12px;" placeholder="Street address" required></div>
                        <div class="col-6"><input type="text" name="city" class="fp-chk-select" style="padding:10px 12px;" placeholder="City" required></div>
                        <div class="col-6"><input type="text" name="state" class="fp-chk-select" style="padding:10px 12px;" placeholder="State" required></div>
                        <div class="col-12"><input type="text" name="phone" class="fp-chk-select" style="padding:10px 12px;" placeholder="Phone number" required></div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--card-border);padding:16px 24px;">
                    <button type="submit" class="btn-primary-gold w-100 justify-content-center">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selectAddr(el, id) {
    document.querySelectorAll('.addr-card').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    el.querySelector('input[type="radio"]').checked = true;
}

function selectPM(el, type) {
    document.querySelectorAll('.fp-chk-pm-card').forEach(c => {
        const input = c.querySelector('input[name="payment_type"]');
        if (input) c.classList.remove('active');
    });
    el.classList.add('active');
    el.querySelector('input[type="radio"]').checked = true;
    const planSelect = document.getElementById('planSelect');
    if (type === 'installment') {
        planSelect.style.display = 'block';
        planSelect.style.animation = 'fadeIn 0.3s ease';
    } else {
        planSelect.style.display = 'none';
    }
}

function selectGW(el, value) {
    document.querySelectorAll('.fp-chk-pm-card').forEach(c => {
        const input = c.querySelector('input[name="payment_method"]');
        if (input) c.classList.remove('active');
    });
    el.classList.add('active');
    el.querySelector('input[type="radio"]').checked = true;
}

document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('placeOrderBtn');
    if (btn.disabled) { e.preventDefault(); return; }
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:16px;height:16px;"></span> Processing...';
});

// ===== PLAN-SCOPED TERMS: surface the selected plan's T&C before checkout =====
(function () {
    const planTerms = @json($termsByPlan ?? []);
    const note = document.getElementById('planTermsNote');
    const link = document.getElementById('planTermsLink');
    if (!note) return;

    function update(planId) {
        const entry = planTerms[planId];
        if (entry) {
            link.textContent = entry.title;
            link.href = entry.url;
            note.style.display = 'block';
        } else {
            note.style.display = 'none';
        }
    }

    Livewire.on('checkout-plan-changed', ({ planId }) => update(planId));
})();

// ===== DELIVERY PROXY: search a registered user, confirm, assign =====
(function () {
    const searchInput = document.getElementById('proxySearchInput');
    const searchBtn = document.getElementById('proxySearchBtn');
    const results = document.getElementById('proxyResults');
    const assigned = document.getElementById('proxyAssigned');
    const assignedName = document.getElementById('proxyAssignedName');
    const hidden = document.getElementById('deliveryProxyUserId');
    const clearBtn = document.getElementById('proxyClear');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    async function search() {
        const q = searchInput.value.trim();
        if (q.length < 3) { results.innerHTML = '<small style="color:var(--text-dim);font-size:12px;">Type at least 3 characters to search.</small>'; return; }
        searchBtn.disabled = true;
        searchBtn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:12px;height:12px;"></span>';
        try {
            const res = await fetch('/checkout/proxy/search?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf }
            });
            const data = await res.json();
            renderResults(data.users || []);
        } catch (e) {
            results.innerHTML = '<small style="color:#ef4444;font-size:12px;">Could not search. Try again.</small>';
        } finally {
            searchBtn.disabled = false;
            searchBtn.innerHTML = '<i class="bi bi-search"></i> Find';
        }
    }

    function renderResults(users) {
        if (!users.length) {
            results.innerHTML = '<small style="color:var(--text-dim);font-size:12px;">No matching store members found.</small>';
            return;
        }
        results.innerHTML = users.map(u => `
            <div class="fp-chk-radio-card mb-2" style="padding:12px 14px;cursor:default;">
                <div style="flex:1;min-width:0;">
                    <strong style="color:var(--text-primary);font-size:13px;display:block;">${esc(u.name)}</strong>
                    <span style="color:var(--text-dim);font-size:12px;">${esc(u.email || '')}${u.phone ? ' · ' + esc(u.phone) : ''}</span>
                </div>
                <button type="button" class="fp-btn fp-btn-gold" data-id="${u.id}" data-name="${escAttr(u.name)}" style="padding:6px 14px;font-size:12px;flex-shrink:0;">Assign</button>
            </div>
        `).join('');
        results.querySelectorAll('button[data-id]').forEach(btn => {
            btn.addEventListener('click', () => confirmAssign(btn.dataset.id, btn.dataset.name));
        });
    }

    function confirmAssign(id, name) {
        // Confirmation step before the proxy is actually assigned.
        const ok = window.confirm('Assign ' + name + ' to receive this delivery on your behalf?');
        if (!ok) return;
        hidden.value = id;
        assignedName.textContent = name;
        assigned.style.display = 'block';
        results.innerHTML = '';
        searchInput.value = '';
    }

    function esc(s) {
        const d = document.createElement('div');
        d.textContent = s || '';
        return d.innerHTML;
    }
    function escAttr(s) {
        return esc(s).replace(/"/g, '&quot;');
    }

    searchBtn?.addEventListener('click', search);
    searchInput?.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); search(); } });
    clearBtn?.addEventListener('click', e => {
        e.preventDefault();
        hidden.value = '';
        assigned.style.display = 'none';
    });
})();
</script>
