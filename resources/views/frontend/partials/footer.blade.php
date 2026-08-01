@php
use App\Models\Setting;
$settings = Setting::first();
$email = $settings?->email ?? 'support@ownpace.store';
$phone = $settings?->phone ?? '+234 800-OWNPACE';
$location = $settings?->location ?? 'Lagos, Nigeria';
@endphp

<footer class="fp-footer">
    <div class="fp-newsletter">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <div class="fp-nl-content reveal-left">
                        <div class="fp-nl-icon-wrap">
                            <i class="bi bi-envelope-paper-fill fp-nl-icon"></i>
                        </div>
                        <div>
                            <h4>Stay in the Loop</h4>
                            <p>Get exclusive deals, payment tips, and new arrivals</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form class="fp-nl-form reveal-right" onsubmit="handleNLSubmit(event)">
                        <div class="fp-nl-input-wrap">
                            <i class="bi bi-envelope-fill"></i>
                            <input type="email" id="newsletter-email" name="email" placeholder="Enter your email address" required aria-label="Email for newsletter">
                        </div>
                        <button type="submit"><i class="bi bi-send-fill"></i> Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="fp-footer-main">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <div class="fp-footer-brand">
                        <div class="fp-footer-logo">
                            <div class="fp-footer-logo-icon"><i class="bi bi-currency-exchange"></i></div>
                            <span>Flexi<span>Pay</span></span>
                        </div>
                        <p>Nigeria's premier installment payment platform. Shop what you love today and pay over time with flexible plans designed for your budget.</p>
                        <div class="fp-footer-info">
                            <div class="fp-fi-item"><i class="bi bi-geo-alt-fill"></i><span>{{ $location }}</span></div>
                            <div class="fp-fi-item"><i class="bi bi-telephone-fill"></i><span>{{ $phone }}</span></div>
                            <div class="fp-fi-item"><i class="bi bi-envelope-fill"></i><span>{{ $email }}</span></div>
                            <div class="fp-fi-item"><i class="bi bi-clock-fill"></i><span>Mon–Sat: 8AM – 6PM (WAT)</span></div>
                        </div>
                        <div class="fp-social-links">
                            <a href="#" class="fp-social-btn facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="fp-social-btn twitter"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="fp-social-btn instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="fp-social-btn whatsapp"><i class="bi bi-whatsapp"></i></a>
                            <a href="#" class="fp-social-btn youtube"><i class="bi bi-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 col-6">
                    <div class="fp-footer-col">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li><a href="{{ url('/shop') }}">Shop</a></li>
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="{{ url('/contact') }}">Contact</a></li>
                            <li><a href="{{ url('/faq') }}">FAQs</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6">
                    <div class="fp-footer-col">
                        <h5>Customer Service</h5>
                        <ul>
                            <li><a href="{{ url('/terms') }}">Terms & Conditions</a></li>
                            <li><a href="{{ url('/terms/payment') }}">Payment Plans</a></li>
                            <li><a href="{{ url('/terms/delivery') }}">Delivery Policy</a></li>
                            <li><a href="{{ url('/terms/returns') }}">Returns & Exchanges</a></li>
                            <li><a href="{{ url('/terms/privacy') }}">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="fp-footer-col">
                        <h5>Pay With</h5>
                        <div class="fp-payment-methods">
                            <span class="fp-pm-item"><i class="bi bi-credit-card-fill"></i> Credit/Debit</span>
                            <span class="fp-pm-item"><i class="bi bi-bank"></i> Bank Transfer</span>
                            <span class="fp-pm-item"><i class="bi bi-phone-fill"></i> USSD</span>
                            <span class="fp-pm-item"><i class="bi bi-wallet2"></i> Wallet</span>
                        </div>
                        <div class="fp-trust-badges mt-3">
                            <div class="fp-trust-badge"><i class="bi bi-shield-fill-check"></i> Secured Payments</div>
                            <div class="fp-trust-badge"><i class="bi bi-patch-check-fill"></i> Verified Store</div>
                            <div class="fp-trust-badge"><i class="bi bi-clock-history"></i> Flexible Plans</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="fp-footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p>&copy; {{ date('Y') }} <span>OwnPace Store</span> — All rights reserved. Made with <i class="bi bi-heart-fill"></i> in Nigeria | Developed by Joty Biswas</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <a href="{{ url('/terms/privacy') }}">Privacy</a>
                    <span class="fp-sep">|</span>
                    <a href="{{ url('/terms') }}">Terms</a>
                    <span class="fp-sep">|</span>
                    <a href="{{ url('/contact') }}">Support</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* ===== FOOTER BASE ===== */
.fp-footer {
    background: linear-gradient(180deg, var(--dark-900) 0%, var(--dark-950) 100%);
    position: relative;
}
.fp-footer::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(234,179,8,0.12), transparent);
}

/* ===== NEWSLETTER ===== */
.fp-newsletter {
    background: linear-gradient(135deg, #a16207, #854d0e);
    padding: 36px 0;
    position: relative; overflow: hidden;
}
.fp-newsletter::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 80% 100% at 0% 50%, rgba(255,255,255,0.06) 0%, transparent 60%),
        radial-gradient(ellipse 60% 80% at 100% 50%, rgba(255,255,255,0.04) 0%, transparent 50%);
    pointer-events: none;
}
.fp-newsletter::after {
    content: ''; position: absolute;
    top: -50%; left: -20%; width: 200px; height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
    border-radius: 50%;
    animation: nlOrb 6s ease-in-out infinite alternate;
    pointer-events: none;
}
@keyframes nlOrb {
    0% { transform: translate(0,0) scale(1); }
    100% { transform: translate(40px,30px) scale(1.3); }
}

.fp-nl-content { display: flex; align-items: center; gap: 16px; color: var(--near-black); position: relative; z-index: 1; }
.fp-nl-icon-wrap {
    flex-shrink: 0;
    position: relative;
}
.fp-nl-icon {
    font-size: 34px;
    color: rgba(0,0,0,0.3);
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    animation: nlIconFloat 3s ease-in-out infinite;
}
@keyframes nlIconFloat {
    0%,100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-5px) rotate(-3deg); }
}
.fp-nl-content h4 {
    font-family: 'Syne', sans-serif;
    font-size: 19px; font-weight: 800;
    margin-bottom: 3px; color: var(--near-black);
}
.fp-nl-content p { font-size: 14px; color: rgba(0,0,0,0.6); margin: 0; }

.fp-nl-form {
    display: flex; gap: 0; border-radius: 12px; overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.25);
    transition: box-shadow 0.4s ease, transform 0.3s;
    position: relative; z-index: 1;
}
.fp-nl-form:focus-within {
    box-shadow: 0 12px 40px rgba(0,0,0,0.35);
    transform: scale(1.01);
}
.fp-nl-input-wrap {
    flex: 1; display: flex; align-items: center; gap: 10px;
    padding: 0 18px;
    background: rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.15);
    border-right: none;
    transition: background 0.3s;
}
.fp-nl-input-wrap:focus-within {
    background: rgba(0,0,0,0.3);
}
.fp-nl-input-wrap i {
    color: rgba(255,255,255,0.4);
    font-size: 16px;
    transition: color 0.3s;
}
.fp-nl-input-wrap:focus-within i { color: var(--gold-400); }
.fp-nl-input-wrap input {
    flex: 1; padding: 14px 0; border: none;
    background: transparent; color: white;
    font-size: 14px; outline: none; font-family: inherit;
}
.fp-nl-input-wrap input::placeholder { color: rgba(255,255,255,0.5); }
.fp-nl-form button {
    background: var(--near-black); color: var(--gold-400); border: none;
    padding: 14px 28px; font-weight: 700; font-size: 14px;
    cursor: pointer; display: flex; align-items: center; gap: 8px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family: inherit; white-space: nowrap;
    position: relative; overflow: hidden;
}
.fp-nl-form button::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.08) 50%, transparent 80%);
    transform: translateX(-100%); transition: transform 0.5s;
}
.fp-nl-form button:hover::before { transform: translateX(100%); }
.fp-nl-form button:hover {
    background: var(--dark-900);
    color: var(--gold-300);
    transform: scale(1.03);
}
.fp-nl-form button:active { transform: scale(0.97); }

/* ===== MAIN FOOTER ===== */
.fp-footer-main {
    padding: 64px 0 44px;
    position: relative;
}
.fp-footer-main::before {
    content: ''; position: absolute; top: 0; left: 10%; right: 10%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(234,179,8,0.08), transparent);
}

.fp-footer-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.fp-footer-logo-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-700));
    display: flex; align-items: center; justify-content: center;
    color: var(--near-black); font-size: 20px;
    box-shadow: var(--shadow-gold);
    transition: transform 0.3s;
}
.fp-footer-logo:hover .fp-footer-logo-icon {
    transform: rotate(-8deg) scale(1.08);
}
.fp-footer-logo span {
    font-family: 'Syne', sans-serif;
    font-size: 22px; font-weight: 800;
    color: var(--text-primary);
}
.fp-footer-logo span span { color: var(--gold-500); }
.fp-footer-brand p {
    color: var(--text-dim);
    font-size: 14px; line-height: 1.8;
    margin-bottom: 24px; max-width: 360px;
}

.fp-footer-info {
    display: flex; flex-direction: column; gap: 10px;
    margin-bottom: 24px;
}
.fp-fi-item {
    display: flex; align-items: center; gap: 10px;
    color: var(--text-dim); font-size: 13.5px;
    transition: color 0.3s;
}
.fp-fi-item:hover { color: var(--text-muted); }
.fp-fi-item i {
    color: var(--gold-500);
    font-size: 13px; width: 18px;
    text-align: center;
    flex-shrink: 0;
    transition: transform 0.3s;
}
.fp-fi-item:hover i { transform: scale(1.15); }

.fp-social-links { display: flex; gap: 8px; }
.fp-social-btn {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; color: var(--text-muted);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid var(--card-border);
    position: relative;
    overflow: hidden;
}
.fp-social-btn::before {
    content: ''; position: absolute; inset: 0;
    opacity: 0; transition: opacity 0.3s;
}
.fp-social-btn:hover { transform: translateY(-4px); color: white; border-color: transparent; }
.fp-social-btn.facebook:hover { background: #1877f2; box-shadow: 0 4px 15px rgba(24,119,242,0.3); }
.fp-social-btn.twitter:hover { background: #000; box-shadow: 0 4px 15px rgba(0,0,0,0.2); border-color: #333; }
.fp-social-btn.instagram:hover {
    background: linear-gradient(135deg, #f58529, #dd2a7b, #8134af);
    box-shadow: 0 4px 15px rgba(225,48,108,0.3);
}
.fp-social-btn.whatsapp:hover { background: #25d366; box-shadow: 0 4px 15px rgba(37,211,102,0.3); }
.fp-social-btn.youtube:hover { background: #ff0000; box-shadow: 0 4px 15px rgba(255,0,0,0.3); }

/* ===== FOOTER COLUMNS ===== */
.fp-footer-col h5 {
    color: var(--text-primary);
    font-size: 14px; font-weight: 700;
    margin-bottom: 18px; padding-bottom: 10px;
    border-bottom: 2px solid rgba(234,179,8,0.2);
    position: relative;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.fp-footer-col h5::after {
    content: ''; position: absolute; bottom: -2px; left: 0;
    width: 36px; height: 2px; background: var(--gold-500);
    border-radius: 1px;
}
.fp-footer-col ul {
    list-style: none;
    display: flex; flex-direction: column; gap: 6px;
}
.fp-footer-col ul li a {
    color: var(--text-dim);
    font-size: 14px;
    display: inline-flex; align-items: center; gap: 6px;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    padding: 3px 0;
    position: relative;
}
.fp-footer-col ul li a::before {
    content: '›';
    color: var(--gold-500);
    font-size: 16px; font-weight: 700;
    transition: transform 0.3s, color 0.3s;
}
.fp-footer-col ul li a:hover {
    color: var(--gold-400);
    padding-left: 6px;
}
.fp-footer-col ul li a:hover::before {
    transform: translateX(4px);
    color: var(--gold-400);
}

/* ===== PAYMENT METHODS ===== */
.fp-payment-methods {
    display: flex; flex-wrap: wrap; gap: 6px;
}
.fp-pm-item {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    color: var(--text-muted);
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 12px; font-weight: 500;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-pm-item:hover {
    border-color: rgba(234,179,8,0.3);
    background: rgba(234,179,8,0.06);
    color: var(--gold-400);
    transform: translateY(-2px);
}
.fp-pm-item i {
    color: var(--gold-500);
    font-size: 13px;
    transition: transform 0.3s;
}
.fp-pm-item:hover i { transform: scale(1.15); }

/* ===== TRUST BADGES ===== */
.fp-trust-badges {
    display: flex; flex-direction: column; gap: 6px;
}
.fp-trust-badge {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 12px; font-weight: 500;
    color: var(--text-dim);
    transition: color 0.3s;
}
.fp-trust-badge:hover { color: var(--text-muted); }
.fp-trust-badge i {
    color: var(--gold-500);
    font-size: 13px;
    transition: transform 0.3s;
}
.fp-trust-badge:hover i { transform: scale(1.2); }

/* ===== FOOTER BOTTOM ===== */
.fp-footer-bottom {
    border-top: 1px solid rgba(234,179,8,0.08);
    padding: 20px 0;
    background: rgba(0,0,0,0.35);
    position: relative;
}
.fp-footer-bottom::before {
    content: ''; position: absolute; top: -1px; left: 20%; right: 20%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(234,179,8,0.15), transparent);
}
.fp-footer-bottom p {
    color: var(--text-dim);
    font-size: 13px; margin: 0;
}
.fp-footer-bottom p span {
    color: var(--gold-500);
    font-weight: 700;
}
.fp-footer-bottom p i {
    color: #ef4444;
    display: inline-block;
    animation: heartBeat 1.5s ease-in-out infinite;
}
@keyframes heartBeat {
    0%,100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}
.fp-footer-bottom a {
    color: var(--text-dim);
    font-size: 13px;
    transition: color 0.3s;
    position: relative;
}
.fp-footer-bottom a::after {
    content: ''; position: absolute; bottom: -1px; left: 0; right: 0;
    height: 1px; background: var(--gold-500);
    transform: scaleX(0); transition: transform 0.3s;
    transform-origin: left;
}
.fp-footer-bottom a:hover {
    color: var(--gold-400);
}
.fp-footer-bottom a:hover::after {
    transform: scaleX(1);
}
.fp-sep {
    color: var(--card-border);
    margin: 0 10px;
    font-size: 11px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .fp-nl-content { margin-bottom: 12px; }
    .fp-footer-main { padding: 44px 0 32px; }
}
@media (max-width: 768px) {
    .fp-newsletter { padding: 28px 0; }
    .fp-nl-content h4 { font-size: 17px; }
}
@media (max-width: 576px) {
    .fp-nl-form { flex-direction: column; border-radius: 10px; }
    .fp-nl-input-wrap {
        border-right: 1px solid rgba(255,255,255,0.15);
        border-radius: 10px 10px 0 0;
    }
    .fp-nl-form button {
        justify-content: center;
        border-radius: 0 0 10px 10px;
        padding: 12px;
    }
    .fp-footer-bottom .row > div { text-align: center !important; }
    .fp-footer-bottom .row > div:last-child { margin-top: 8px; }
}
</style>

<script>
function handleNLSubmit(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button');
    btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Subscribed!';
    btn.style.background = 'var(--gold-500)';
    btn.style.color = '#000';
    setTimeout(() => {
        btn.innerHTML = '<i class="bi bi-send-fill"></i> Subscribe';
        btn.style.background = '';
        btn.style.color = '';
        e.target.querySelector('input').value = '';
    }, 3000);
}
</script>