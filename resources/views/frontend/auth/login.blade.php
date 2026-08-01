@extends('frontend.auth_layout')
@section('title', 'Sign In')

@section('content')
<style>
#authParticles {
    position: fixed; inset: 0; z-index: 0;
    pointer-events: none; opacity: 0.4;
}

.fp-bg {
    position: fixed; inset: 0; z-index: 0; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #121214 50%, #0A0A0B 100%);
}
.fp-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(234,179,8,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.03) 1px, transparent 1px);
    background-size: 48px 48px;
    animation: gridDrift 20s linear infinite;
    will-change: transform;
}
@keyframes gridDrift {
    0% { transform: translate(0,0); }
    100% { transform: translate(48px, 48px); }
}
.fp-blob {
    position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.3;
    animation: blobFloat 8s ease-in-out infinite alternate;
    will-change: transform;
}
.fp-blob-1 { width: 560px; height: 560px; background: radial-gradient(circle, #EAB30833, transparent); top: -120px; left: -120px; }
.fp-blob-2 { width: 420px; height: 420px; background: radial-gradient(circle, #CA8A0422, transparent); bottom: -100px; right: -80px; animation-delay: -3s; }
.fp-blob-3 { width: 300px; height: 300px; background: radial-gradient(circle, #EAB30822, transparent); top: 50%; right: 15%; animation-delay: -5s; }
@keyframes blobFloat {
    0% { transform: translate(0,0) scale(1); }
    100% { transform: translate(30px,40px) scale(1.08); }
}
.fp-ring {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%,-50%); border-radius: 50%;
    border: 1px solid rgba(234,179,8,0.08);
    animation: radarPulse 4s ease-out infinite;
}
.fp-ring:nth-child(5) { width: 300px; height: 300px; animation-delay: 0s; }
.fp-ring:nth-child(6) { width: 500px; height: 500px; animation-delay: 1.3s; }
.fp-ring:nth-child(7) { width: 700px; height: 700px; animation-delay: 2.6s; }
@keyframes radarPulse {
    0%   { transform: translate(-50%,-50%) scale(0.6); opacity: 0.4; }
    80%  { opacity: 0.05; }
    100% { transform: translate(-50%,-50%) scale(1.4); opacity: 0; }
}
.fp-float-icon {
    position: absolute; width: 44px; height: 44px; border-radius: 12px;
    background: var(--card-dark); -webkit-backdrop-filter: blur(8px);
    backdrop-filter: blur(8px);
    border: 1px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: var(--gold-400); box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    animation: iconFloat 6s ease-in-out infinite alternate;
    will-change: transform;
}
.fp-float-icon:nth-child(8)  { top: 12%; left: 6%;  animation-delay: 0s;    animation-duration: 7s; }
.fp-float-icon:nth-child(9)  { top: 22%; right: 7%; animation-delay: -2s;   animation-duration: 8s; }
.fp-float-icon:nth-child(10) { bottom: 30%; left: 5%; animation-delay: -1s; animation-duration: 6s; }
.fp-float-icon:nth-child(11) { top: 60%; right: 6%; animation-delay: -3.5s; animation-duration: 9s; }
.fp-float-icon:nth-child(12) { bottom: 12%; left: 18%; animation-delay: -4s; animation-duration: 7s; }
.fp-float-icon:nth-child(13) { top: 8%; right: 22%;  animation-delay: -0.5s; animation-duration: 8s; }
@keyframes iconFloat {
    0%   { transform: translateY(0px) rotate(-4deg); }
    100% { transform: translateY(-20px) rotate(4deg); }
}

.fp-wrapper {
    position: relative; z-index: 1;
    min-height: calc(100vh - 68px);
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 40px 16px;
}

.fp-brand-top {
    display: flex; flex-direction: column; align-items: center; gap: 8px; margin-bottom: 28px;
    animation: fadeDown 0.7s ease both;
}
@keyframes fadeDown { from { opacity: 0; transform: translateY(-24px); } to { opacity: 1; transform: translateY(0); } }
.fp-logo-wrap { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.fp-logo-icon {
    width: 54px; height: 54px; border-radius: 16px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; color: var(--near-black);
    box-shadow: var(--shadow-gold);
    animation: logoPulse 3s ease-in-out infinite;
}
@keyframes logoPulse {
    0%,100% { box-shadow: 0 8px 28px rgba(234,179,8,0.3); }
    50% { box-shadow: 0 8px 40px rgba(234,179,8,0.5); }
}
.fp-logo-text { font-family: 'Syne', sans-serif; font-size: 30px; font-weight: 800; color: var(--text-primary); }
.fp-logo-text span { color: var(--gold-500); }
.fp-tagline { font-size: 12.5px; font-weight: 500; color: var(--text-dim); letter-spacing: 0.3px; display: flex; align-items: center; gap: 6px; }
.fp-tagline i { color: var(--gold-500); font-size: 11px; }

.fp-card {
    width: 100%; max-width: 460px;
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: 24px;
    box-shadow: 0 16px 60px rgba(0,0,0,0.4);
    overflow: hidden; contain: layout style; min-width: 0;
    animation: fadeUp 0.8s cubic-bezier(.22,.68,0,1.2) 0.1s both;
}
@keyframes fadeUp { from { opacity: 0; transform: translateY(40px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }

.fp-card-strip {
    background: linear-gradient(105deg, var(--gold-500) 0%, var(--gold-600) 60%, var(--gold-700) 100%);
    padding: 22px 28px 20px;
    position: relative; overflow: hidden;
}
.fp-card-strip::before {
    content: ''; position: absolute;
    top: -40px; right: -40px; width: 140px; height: 140px;
    border-radius: 50%; background: rgba(0,0,0,0.08);
}
.fp-card-strip::after {
    content: ''; position: absolute;
    bottom: -50px; left: 30%; width: 180px; height: 180px;
    border-radius: 50%; background: rgba(0,0,0,0.04);
}
.fp-strip-inner { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; }
.fp-strip-left h2 { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 700; color: var(--near-black); margin-bottom: 3px; }
.fp-strip-left p { font-size: 13px; color: rgba(0,0,0,0.6); }
.fp-strip-badge {
    display: flex; align-items: center; gap: 7px;
    background: rgba(0,0,0,0.12);
    border: 1px solid rgba(0,0,0,0.2);
    border-radius: 30px; padding: 6px 14px;
    font-size: 12px; color: rgba(0,0,0,0.8); font-weight: 500;
    -webkit-backdrop-filter: blur(4px);
    backdrop-filter: blur(4px);
}
.fp-strip-shine {
    position: absolute; inset: 0;
    background: linear-gradient(105deg, transparent 30%, rgba(255,255,255,0.08) 50%, transparent 70%);
    transform: translateX(-100%);
    animation: stripShine 6s ease-in-out infinite;
}
@keyframes stripShine { 0%,100%{transform:translateX(-100%)} 50%{transform:translateX(100%)} }
.fp-live-dot { width: 8px; height: 8px; background: #000; border-radius: 50%; box-shadow: 0 0 0 0 rgba(0,0,0,0.4); animation: livePing 1.5s ease-in-out infinite; }
@keyframes livePing { 0%,100% { box-shadow: 0 0 0 0 rgba(0,0,0,0.4); } 50% { box-shadow: 0 0 0 6px rgba(0,0,0,0); } }

.fp-card-body { padding: 28px 32px 24px; }

.fp-section-label {
    font-family: 'Syne', sans-serif;
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    text-transform: uppercase; color: var(--gold-500);
    margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
}
.fp-section-label::after { content: ''; flex: 1; height: 1px; background: var(--card-border); }

.fp-field { margin-bottom: 18px; }
.fp-label { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; color: var(--text-primary); margin-bottom: 8px; }
.fp-label i { color: var(--gold-500); font-size: 14px; }
.fp-input-wrap { position: relative; }
.fp-input {
    width: 100%; height: 48px;
    padding: 0 46px 0 16px;
    border: 1.5px solid var(--card-border);
    border-radius: 10px;
    background: var(--surface-dark);
    font-family: 'Space Grotesk', sans-serif;
    font-size: 14px; color: var(--text-primary);
    outline: none; transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
}
.fp-input::placeholder { color: var(--text-dim); }
.fp-input:focus { border-color: var(--gold-500); background: rgba(234,179,8,0.04); box-shadow: 0 0 0 4px rgba(234,179,8,0.08); }
.fp-input.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,0.08); }
.fp-input-focus-glow {
    position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
    width: 0; height: 2px;
    background: linear-gradient(90deg, var(--gold-500), var(--gold-400));
    border-radius: 2px;
    transition: width 0.3s;
}
.fp-input:focus ~ .fp-input-focus-glow { width: 80%; }
.fp-input-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--text-dim); font-size: 16px; pointer-events: none; }
.fp-toggle-btn {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer;
    color: var(--text-dim); font-size: 16px;
    display: flex; align-items: center; padding: 8px; border-radius: 6px;
    touch-action: manipulation;
}
.fp-toggle-btn:hover { color: var(--gold-500); }
.invalid-feedback { display: flex; align-items: center; gap: 5px; font-size: 12px; color: #ef4444; margin-top: 6px; font-weight: 500; }

.fp-row-options { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; }
.fp-check-label { display: flex; align-items: center; gap: 7px; font-size: 13px; color: var(--text-muted); cursor: pointer; }
.fp-check-label input[type=checkbox] { width: 16px; height: 16px; accent-color: var(--gold-500); cursor: pointer; }
.fp-forgot { display: flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 500; color: var(--gold-400); transition: color 0.2s; }
.fp-forgot:hover { color: var(--gold-300); }

.fp-submit-btn {
    width: 100%; height: 50px; border: none;
    border-radius: 10px;
    background: linear-gradient(105deg, var(--gold-500), var(--gold-600));
    color: var(--near-black);
    font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700;
    cursor: pointer; display: flex; align-items: center;
    justify-content: center; gap: 10px;
    box-shadow: 0 6px 24px rgba(234,179,8,0.3);
    transition: transform 0.2s, box-shadow 0.2s; position: relative; overflow: hidden;
    letter-spacing: 0.3px;
}
.fp-submit-btn::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(105deg, transparent 20%, rgba(255,255,255,0.15) 50%, transparent 80%);
    transform: translateX(-100%); transition: transform 0.6s;
}
.fp-submit-btn:hover::before { transform: translateX(100%); }
.fp-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(234,179,8,0.4); }
.fp-submit-btn:active { transform: translateY(0); }

.btn-spinner { display: none; animation: spin 0.7s linear infinite; }
.fp-submit-btn.loading .btn-main-icon { display: none; }
.fp-submit-btn.loading .btn-spinner { display: inline-block; }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

.fp-social-login {
    display: flex; gap: 10px; margin-bottom: 20px;
}
.fp-social-btn {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 10px; border-radius: 10px;
    background: var(--surface-dark); border: 1px solid var(--card-border);
    color: var(--text-muted); font-size: 13px; font-weight: 600;
    cursor: pointer; transition: border-color 0.2s, background 0.2s, color 0.2s; font-family: inherit;
    text-decoration: none; touch-action: manipulation; contain: layout style;
}
.fp-social-btn i { font-size: 18px; }
.fp-social-btn:hover { border-color: rgba(234,179,8,0.2); background: rgba(234,179,8,0.04); }
.fp-social-btn.google:hover { border-color: rgba(234,67,53,0.3); color: #ea4335; }
.fp-social-btn.apple:hover { border-color: rgba(255,255,255,0.3); color: white; }
.fp-social-divider { display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--text-dim); margin: 20px 0; font-weight: 500; }
.fp-social-divider::before, .fp-social-divider::after { content: ''; flex: 1; height: 1px; background: var(--card-border); }

.fp-register-box {
    background: rgba(234,179,8,0.04);
    border: 1px solid rgba(234,179,8,0.15);
    border-radius: 12px;
    padding: 18px 20px; text-align: center; margin-bottom: 20px;
    contain: layout style;
}
.fp-register-box p { font-size: 13px; color: var(--text-muted); margin-bottom: 12px; display: flex; align-items: center; justify-content: center; gap: 6px; }
.fp-register-box p i { color: var(--gold-500); }
.fp-register-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 10px;
    background: linear-gradient(105deg, var(--gold-500), var(--gold-600));
    color: var(--near-black);
    font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700;
    box-shadow: var(--shadow-gold); transition: transform 0.18s, box-shadow 0.18s;
}
.fp-register-btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-gold-lg); color: var(--near-black); }

.fp-trust-row { display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; }
.fp-trust-item {
    display: flex; align-items: center; gap: 5px;
    font-size: 11.5px; font-weight: 500; color: var(--text-dim);
    background: var(--surface-dark); border: 1px solid var(--card-border);
    border-radius: 20px; padding: 5px 12px;
}
.fp-trust-item i { color: var(--gold-500); font-size: 12px; }

.fp-card-footer {
    padding: 14px 28px; background: var(--surface-dark);
    border-top: 1px solid var(--card-border);
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
}
.fp-footer-branding { display: flex; align-items: center; gap: 7px; font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700; color: var(--gold-500); }
.fp-footer-links { display: flex; gap: 16px; }
.fp-footer-links a { font-size: 12px; color: var(--text-dim); transition: color 0.2s; }
.fp-footer-links a:hover { color: var(--gold-400); }

.fp-stats-row {
    display: flex; gap: 32px; margin-top: 28px;
    animation: fadeUp 0.9s cubic-bezier(.22,.68,0,1.2) 0.35s both;
    content-visibility: auto;
}
.fp-stat { text-align: center; }
.fp-stat-num { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: var(--gold-500); line-height: 1; }
.fp-stat-num span { color: var(--gold-400); font-size: 18px; }
.fp-stat-label { font-size: 11px; color: var(--text-muted); font-weight: 500; margin-top: 3px; }
.fp-location-tag { margin-top: 14px; display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--text-dim); animation: fadeUp 1s cubic-bezier(.22,.68,0,1.2) 0.45s both; }
.fp-location-tag i { color: var(--gold-500); }

@media (max-width: 520px) {
    .fp-card-body { padding: 22px 20px 20px; }
    .fp-card-footer { padding: 12px 20px; }
    .fp-card-strip { padding: 18px 20px 16px; }
    .fp-stats-row { gap: 20px; }
    .fp-social-login { flex-direction: column; }
}

@media (prefers-reduced-motion: reduce) {
    .fp-grid, .fp-blob, .fp-ring, .fp-float-icon, .fp-strip-shine,
    .fp-live-dot, .fp-logo-icon,
    .fp-brand-top, .fp-card, .fp-stats-row, .fp-location-tag {
        animation: none !important;
    }
    #authParticles { display: none; }
}
</style>

<canvas id="authParticles" aria-hidden="true"></canvas>

<div class="fp-bg">
    <div class="fp-grid"></div>
    <div class="fp-blob fp-blob-1"></div>
    <div class="fp-blob fp-blob-2"></div>
    <div class="fp-blob fp-blob-3"></div>
    <div class="fp-ring"></div>
    <div class="fp-ring"></div>
    <div class="fp-ring"></div>
    <div class="fp-float-icon" aria-hidden="true"><i class="bi bi-phone"></i></div>
    <div class="fp-float-icon" aria-hidden="true"><i class="bi bi-laptop"></i></div>
    <div class="fp-float-icon" aria-hidden="true"><i class="bi bi-tv"></i></div>
    <div class="fp-float-icon" aria-hidden="true"><i class="bi bi-headphones"></i></div>
    <div class="fp-float-icon" aria-hidden="true"><i class="bi bi-watch"></i></div>
    <div class="fp-float-icon" aria-hidden="true"><i class="bi bi-camera"></i></div>
</div>

<div class="fp-wrapper">
    <div class="fp-brand-top">
        <a href="{{ url('/') }}" class="fp-logo-wrap">
            <div class="fp-logo-icon"><i class="bi bi-currency-exchange"></i></div>
            <div class="fp-logo-text"><span>Flexi</span>Pay</div>
        </a>
        <div class="fp-tagline"><i class="bi bi-lightning-charge-fill"></i> Buy Now, Pay in Installments <i class="bi bi-lightning-charge-fill"></i></div>
    </div>

    <div class="fp-card" id="loginCard">
        <div class="fp-card-strip">
            <div class="fp-strip-shine"></div>
            <div class="fp-strip-inner">
                <div class="fp-strip-left">
                    <h2>Welcome Back!</h2>
                    <p>Sign in to manage your purchases</p>
                </div>
                <div class="fp-strip-badge">
                    <div class="fp-live-dot"></div>
                    Secure Login
                </div>
            </div>
        </div>

        <div class="fp-card-body">
            <div class="fp-section-label">Customer Login</div>

            <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                @csrf

                <div class="fp-field">
                    <label for="email" class="fp-label"><i class="bi bi-envelope-fill"></i> Email Address</label>
                    <div class="fp-input-wrap">
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               class="fp-input @error('email') is-invalid @enderror"
                               placeholder="you@example.com" required autocomplete="email" autofocus
                               inputmode="email" aria-describedby="email-error">
                        <i class="bi bi-envelope fp-input-icon" aria-hidden="true"></i>
                        <div class="fp-input-focus-glow"></div>
                    </div>
                    @error('email')
                        <div class="invalid-feedback" id="email-error" role="alert"><i class="bi bi-exclamation-circle-fill"></i> <strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="fp-field">
                    <label for="password" class="fp-label"><i class="bi bi-shield-lock-fill"></i> Password</label>
                    <div class="fp-input-wrap">
                        <input id="password" type="password" name="password"
                               class="fp-input @error('password') is-invalid @enderror"
                               placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required autocomplete="current-password" spellcheck="false"
                               aria-describedby="password-error">
                        <button type="button" class="fp-toggle-btn" id="togglePassword" aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                        <div class="fp-input-focus-glow"></div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback" id="password-error" role="alert"><i class="bi bi-exclamation-circle-fill"></i> <strong>{{ $message }}</strong></div>
                    @enderror
                </div>

                <div class="fp-row-options">
                    <label class="fp-check-label">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <i class="bi bi-bookmark-check" style="color:var(--gold-500);font-size:13px;"></i> Remember Me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="fp-forgot">
                            <i class="bi bi-key-fill"></i> Forgot Password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="fp-submit-btn" id="loginBtn">
                    <i class="bi bi-box-arrow-in-right btn-main-icon"></i>
                    <i class="bi bi-arrow-repeat btn-spinner"></i>
                    Sign In to OwnPace
                </button>
            </form>

            <div class="fp-social-divider" role="separator" aria-label="or sign in with">Sign in faster</div>

            <div class="fp-social-login">
                <a href="{{ url('/auth/google') }}" class="fp-social-btn google">
                    <i class="bi bi-google"></i> Google
                </a>
                <a href="{{ url('/auth/apple') }}" class="fp-social-btn apple">
                    <i class="bi bi-apple"></i> Apple
                </a>
            </div>

            <div class="fp-register-box">
                <p><i class="bi bi-people-fill"></i> Join thousands paying flexibly across Nigeria</p>
                <a href="{{ route('register') }}" class="fp-register-btn">
                    <i class="bi bi-person-plus-fill"></i> Create Free Account
                </a>
            </div>

            <div class="fp-trust-row">
                <div class="fp-trust-item"><i class="bi bi-shield-fill-check"></i> Secured</div>
                <div class="fp-trust-item"><i class="bi bi-patch-check-fill"></i> Verified</div>
                <div class="fp-trust-item"><i class="bi bi-lock-fill"></i> Encrypted</div>
            </div>
        </div>

        <div class="fp-card-footer">
            <div class="fp-footer-branding"><i class="bi bi-currency-exchange"></i> OwnPace &copy; 2025</div>
            <div class="fp-footer-links">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Support</a>
            </div>
        </div>
    </div>

    <div class="fp-stats-row">
        <div class="fp-stat"><div class="fp-stat-num" data-count="5000">0<span>+</span></div><div class="fp-stat-label">Products</div></div>
        <div class="fp-stat"><div class="fp-stat-num" data-count="15000">0<span>+</span></div><div class="fp-stat-label">Happy Customers</div></div>
        <div class="fp-stat"><div class="fp-stat-num" data-count="36">0<span>+</span></div><div class="fp-stat-label">Payment Plans</div></div>
    </div>

    <div class="fp-location-tag">
        <i class="bi bi-geo-alt-fill"></i> Serving all across Nigeria — Lagos, Abuja, Port Harcourt &amp; more
    </div>
</div>

<script>
(function() {
    // Password visibility toggle
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        icon.className = isText ? 'bi bi-eye' : 'bi bi-eye-slash';
    });

    // Form loading state
    document.getElementById('loginForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('loginBtn');
        if (btn) {
            btn.classList.add('loading');
            btn.disabled = true;
        }
    });

    // Particle canvas with visibility optimization
    const canvas = document.getElementById('authParticles');
    if (canvas) {
        const ctx = canvas.getContext('2d');
        let W, H, animId;
        const PARTICLE_COUNT = 25;
        const particles = [];

        function resize() {
            W = canvas.width = window.innerWidth;
            H = canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        for (let i = 0; i < PARTICLE_COUNT; i++) {
            particles.push({
                x: Math.random() * W,
                y: Math.random() * H,
                size: Math.random() * 1.5 + 0.5,
                speedX: (Math.random() - 0.5) * 0.3,
                speedY: (Math.random() - 0.5) * 0.3,
                opacity: Math.random() * 0.3 + 0.1
            });
        }

        function animate() {
            ctx.clearRect(0, 0, W, H);
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                p.x += p.speedX;
                p.y += p.speedY;
                if (p.x < 0 || p.x > W) p.speedX *= -1;
                if (p.y < 0 || p.y > H) p.speedY *= -1;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(234,179,8,' + p.opacity + ')';
                ctx.fill();
            }
            animId = requestAnimationFrame(animate);
        }

        // Pause when tab not visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (animId) cancelAnimationFrame(animId);
            } else {
                animate();
            }
        });

        animate();
    }

    // Counter animation for stats using requestAnimationFrame
    const counterObs = new IntersectionObserver(function(entries) {
        for (let i = 0; i < entries.length; i++) {
            const e = entries[i];
            if (!e.isIntersecting) continue;
            const el = e.target;
            const text = el.textContent;
            const match = text.match(/^(\d+)/);
            if (!match) continue;
            const target = parseInt(match[1]);
            const suffix = text.replace(/^[\d,]+/, '');
            const dur = 1200;
            const start = performance.now();

            function tick(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / dur, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const cur = Math.floor(eased * target);
                el.innerHTML = cur.toLocaleString() + suffix;
                if (progress < 1) {
                    requestAnimationFrame(tick);
                } else {
                    el.innerHTML = target.toLocaleString() + suffix;
                }
            }
            requestAnimationFrame(tick);
            counterObs.unobserve(el);
        }
    }, { threshold: 0.3 });
    document.querySelectorAll('.fp-stat-num[data-count]').forEach(function(el) {
        counterObs.observe(el);
    });
})();
</script>
@endsection
