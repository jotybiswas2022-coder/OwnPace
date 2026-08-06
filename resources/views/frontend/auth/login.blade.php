@extends('frontend.auth_layout')
@section('body_class', 'auth-immersive')
@section('title', 'Sign In')

@section('content')
<style>
/* ============================================================
   {{ storeName() }} — Sign In (immersive dark glass)
   Deep-indigo night, drifting aurora, floating gold dust and a
   frosted glass card. Matches the storefront glassmorphism
   system (indigo + mango) while owning the full viewport.
   ============================================================ */
:root {
    --lg-gold: #f5a623;
    --lg-gold-2: #ffce7a;
    --lg-gold-deep: #d98c0f;
    --lg-ink: #f2f1f7;
    --lg-muted: #a8a3c2;
    --lg-dim: #8b86ad;
    --lg-line: rgba(255, 255, 255, 0.12);
    --lg-font-display: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
    --lg-font-body: "Inter", ui-sans-serif, system-ui, sans-serif;
}

#lgParticles {
    position: fixed; inset: 0; z-index: 1;
    pointer-events: none; opacity: 0.45;
}

/* ---- Backdrop ---- */
.lg-bg {
    position: fixed; inset: 0; z-index: 0; overflow: hidden;
    background:
        radial-gradient(1200px 600px at 85% -10%, rgba(74, 69, 153, 0.5), transparent 60%),
        radial-gradient(900px 520px at 0% 110%, rgba(245, 166, 35, 0.14), transparent 55%),
        linear-gradient(165deg, #1b1740 0%, #171531 46%, #0e0c24 100%);
}

.lg-grid {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(245, 166, 35, 0.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(245, 166, 35, 0.035) 1px, transparent 1px);
    background-size: 46px 46px;
    -webkit-mask-image: radial-gradient(ellipse 95% 80% at 50% 38%, #000 25%, transparent 78%);
    mask-image: radial-gradient(ellipse 95% 80% at 50% 38%, #000 25%, transparent 78%);
    animation: lgDrift 22s linear infinite;
    will-change: transform;
}
@keyframes lgDrift { 0% { transform: translate(0, 0); } 100% { transform: translate(46px, 46px); } }

.lg-blob {
    position: absolute; border-radius: 50%; filter: blur(90px); opacity: 0.32;
    animation: lgBlob 11s ease-in-out infinite alternate;
    will-change: transform;
}
.lg-blob-1 { width: 34rem; height: 34rem; top: -10rem; right: -8rem; background: radial-gradient(circle, rgba(245, 166, 35, 0.5), transparent 62%); }
.lg-blob-2 { width: 30rem; height: 30rem; bottom: -9rem; left: -7rem; background: radial-gradient(circle, rgba(74, 69, 153, 0.68), transparent 62%); animation-delay: -5s; }
.lg-blob-3 { width: 22rem; height: 22rem; top: 32%; left: 44%; background: radial-gradient(circle, rgba(245, 166, 35, 0.22), transparent 64%); animation-delay: -9s; }
@keyframes lgBlob {
    0%   { transform: translate(0, 0) scale(1); }
    100% { transform: translate(30px, -26px) scale(1.07); }
}

.lg-grain {
    position: absolute; inset: 0; pointer-events: none; opacity: 0.05;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.7'/%3E%3C/svg%3E");
}

.lg-vignette {
    position: absolute; inset: 0; pointer-events: none;
    background: radial-gradient(ellipse 130% 95% at 50% 42%, transparent 55%, rgba(8, 7, 22, 0.55) 100%);
}

/* ---- Layout shell ---- */
.lg-wrap {
    position: relative; z-index: 2;
    min-height: 100vh;
    min-height: 100dvh;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 3.5rem 1rem 2rem;
}

/* Floating back-to-store pill */
.lg-home {
    position: fixed; top: 1.1rem; left: 1.1rem; z-index: 3;
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.5rem 0.95rem; border-radius: 999px;
    font-size: 0.78rem; font-weight: 600; color: var(--lg-muted); text-decoration: none;
    background: rgba(255, 255, 255, 0.06);
    -webkit-backdrop-filter: blur(14px); backdrop-filter: blur(14px);
    border: 1px solid rgba(255, 255, 255, 0.14);
    transition: color 0.2s ease, border-color 0.2s ease, background 0.2s ease, transform 0.2s ease;
}
.lg-home:hover {
    color: var(--lg-gold-2); text-decoration: none; border-color: rgba(245, 166, 35, 0.5);
    background: rgba(245, 166, 35, 0.1); transform: translateY(-1px);
}

/* ---- Logo ---- */
.lg-logo {
    display: flex; align-items: center; gap: 0.7rem;
    margin-bottom: 1.6rem; text-decoration: none;
    animation: lgDown 0.6s ease both;
}
.lg-logo:hover { text-decoration: none; }
.lg-logo-mark {
    position: relative; display: flex; align-items: center; justify-content: center;
    width: 2.9rem; height: 2.9rem; border-radius: 0.9rem; font-size: 1.2rem; color: #1a1b23;
    background: linear-gradient(135deg, var(--lg-gold) 0%, var(--lg-gold-deep) 100%);
    box-shadow: 0 10px 30px -8px rgba(245, 166, 35, 0.65), inset 0 1px 0 rgba(255, 255, 255, 0.4);
}
.lg-logo-mark::after {
    content: ""; position: absolute; inset: -4px; border-radius: 1.15rem;
    border: 1px solid rgba(245, 166, 35, 0.4);
    animation: lgPulse 2.6s ease-in-out infinite;
}
@keyframes lgPulse { 0%, 100% { opacity: 0.55; transform: scale(1); } 50% { opacity: 0; transform: scale(1.2); } }
.lg-logo-name {
    font-family: var(--lg-font-display); font-size: 1.3rem; font-weight: 700;
    letter-spacing: -0.01em; color: var(--lg-ink);
}
.lg-logo-name span { color: var(--lg-gold); }

/* ---- Glass card ---- */
.lg-card {
    position: relative; overflow: hidden;
    width: 100%; max-width: 26.5rem;
    padding: 2.1rem 2rem 1.8rem;
    border-radius: 1.5rem;
    background: rgba(255, 255, 255, 0.065);
    -webkit-backdrop-filter: blur(26px) saturate(150%);
    backdrop-filter: blur(26px) saturate(150%);
    border: 1px solid rgba(255, 255, 255, 0.14);
    box-shadow: 0 40px 90px -30px rgba(6, 5, 20, 0.85), inset 0 1px 0 rgba(255, 255, 255, 0.18);
    animation: lgUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.08s both;
}
.lg-card::before {
    content: ""; position: absolute; top: 0; left: 12%; right: 12%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(245, 166, 35, 0.85), transparent);
}
.lg-card::after {
    content: ""; position: absolute; top: -5.5rem; left: 50%; transform: translateX(-50%);
    width: 24rem; height: 9.5rem; border-radius: 50%;
    background: radial-gradient(circle, rgba(245, 166, 35, 0.16), transparent 68%);
    pointer-events: none;
}
@keyframes lgUp {
    from { opacity: 0; transform: translateY(34px) scale(0.985); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes lgDown { from { opacity: 0; transform: translateY(-18px); } to { opacity: 1; transform: translateY(0); } }

/* ---- Header ---- */
.lg-head { text-align: center; }
.lg-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.34rem 0.9rem; margin-bottom: 1rem;
    border-radius: 999px; font-size: 0.68rem; font-weight: 700;
    letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--lg-gold-2);
    background: rgba(245, 166, 35, 0.12);
    border: 1px solid rgba(245, 166, 35, 0.3);
}
.lg-head h1 {
    margin: 0; font-family: var(--lg-font-display);
    font-size: 1.8rem; font-weight: 700; letter-spacing: -0.02em; color: var(--lg-ink);
}
.lg-head h1 em {
    font-style: normal;
    background: linear-gradient(92deg, #ffd28a 0%, var(--lg-gold) 50%, #ffe0b2 100%);
    -webkit-background-clip: text; background-clip: text; color: transparent;
}
.lg-head p { margin: 0.5rem 0 0; font-size: 0.85rem; color: var(--lg-muted); }

/* ---- Alert ---- */
.lg-alert {
    display: flex; align-items: flex-start; gap: 0.6rem;
    margin-top: 1.4rem; padding: 0.85rem 1rem; border-radius: 0.85rem;
    font-size: 0.83rem; font-weight: 600; color: #ffb3ab;
    background: rgba(224, 72, 62, 0.12);
    border: 1px solid rgba(224, 72, 62, 0.35);
    animation: lgDown 0.4s ease both;
}

/* ---- Form ---- */
.lg-form { margin-top: 1.6rem; display: flex; flex-direction: column; gap: 1.1rem; }
.lg-label {
    display: block; margin-bottom: 0.5rem;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em;
    text-transform: uppercase; color: var(--lg-muted);
}
.lg-input-wrap { position: relative; }
.lg-input-icon {
    position: absolute; left: 0.95rem; top: 50%; transform: translateY(-50%);
    font-size: 0.95rem; color: var(--lg-dim); pointer-events: none;
    transition: color 0.2s ease;
}
.lg-input {
    width: 100%; height: 3.15rem;
    padding: 0 0.95rem 0 2.7rem;
    border-radius: 0.8rem; font-size: 0.9rem; color: var(--lg-ink); outline: none;
    background: rgba(255, 255, 255, 0.055);
    border: 1.5px solid rgba(255, 255, 255, 0.13);
    transition: border-color 0.2s ease, box-shadow 0.25s ease, background 0.2s ease;
}
.lg-input-has-toggle { padding-right: 3rem; }
.lg-input::placeholder { color: var(--lg-dim); }
.lg-input:focus {
    border-color: rgba(245, 166, 35, 0.75);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 4px rgba(245, 166, 35, 0.14);
}
.lg-input-wrap:focus-within .lg-input-icon { color: var(--lg-gold); }
.lg-input.is-invalid { border-color: rgba(224, 72, 62, 0.6); box-shadow: 0 0 0 4px rgba(224, 72, 62, 0.12); }
.lg-input:-webkit-autofill,
.lg-input:-webkit-autofill:hover,
.lg-input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #262052 inset;
    -webkit-text-fill-color: var(--lg-ink);
    caret-color: var(--lg-ink);
    transition: background-color 9999s ease-in-out 0s;
}
.lg-error {
    display: flex; align-items: center; gap: 0.35rem;
    margin-top: 0.45rem; font-size: 0.75rem; font-weight: 600; color: #ff9d94;
}
.lg-toggle {
    position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%);
    display: flex; align-items: center; justify-content: center;
    width: 2.6rem; height: 2.6rem; border-radius: 0.7rem;
    background: none; border: none; cursor: pointer;
    font-size: 1rem; color: var(--lg-dim);
    transition: color 0.2s ease, background 0.2s ease;
}
.lg-toggle:hover { color: var(--lg-gold); background: rgba(245, 166, 35, 0.1); }

.lg-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; }
.lg-check {
    display: inline-flex; align-items: center; gap: 0.5rem;
    cursor: pointer; user-select: none;
    font-size: 0.82rem; font-weight: 500; color: var(--lg-muted);
}
.lg-check input { position: absolute; opacity: 0; width: 0; height: 0; }
.lg-check-box {
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    width: 1.15rem; height: 1.15rem; border-radius: 0.35rem;
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    background: rgba(255, 255, 255, 0.05);
    transition: background 0.18s ease, border-color 0.18s ease, transform 0.18s ease;
}
.lg-check-box i {
    font-size: 0.68rem; color: #1a1b23;
    opacity: 0; transform: scale(0.4); transition: all 0.18s ease;
}
.lg-check:hover .lg-check-box { border-color: var(--lg-gold); }
.lg-check input:checked + .lg-check-box { background: var(--lg-gold); border-color: var(--lg-gold); }
.lg-check input:checked + .lg-check-box i { opacity: 1; transform: scale(1); }
.lg-check input:focus-visible + .lg-check-box {
    outline: 3px solid rgba(245, 166, 35, 0.55); outline-offset: 2px;
}
.lg-forgot {
    display: inline-flex; align-items: center; gap: 0.35rem;
    font-size: 0.82rem; font-weight: 600; color: var(--lg-gold); text-decoration: none;
    transition: color 0.2s ease;
}
.lg-forgot:hover { color: var(--lg-gold-2); text-decoration: underline; text-underline-offset: 3px; }

/* ---- Submit ---- */
.lg-btn {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 0.55rem;
    width: 100%; height: 3.2rem; margin-top: 0.3rem;
    border: none; border-radius: 0.85rem; cursor: pointer;
    font-family: var(--lg-font-display); font-size: 0.95rem; font-weight: 700; letter-spacing: 0.01em;
    color: #1a1b23;
    background: linear-gradient(135deg, var(--lg-gold) 0%, #f7b733 55%, var(--lg-gold-deep) 100%);
    box-shadow: 0 14px 34px -10px rgba(245, 166, 35, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.45);
    transition: transform 0.18s ease, box-shadow 0.25s ease, filter 0.25s ease;
}
.lg-btn:hover { transform: translateY(-2px); filter: brightness(1.05); box-shadow: 0 20px 44px -12px rgba(245, 166, 35, 0.72); }
.lg-btn:active { transform: translateY(0) scale(0.99); }
.lg-btn:disabled { opacity: 0.75; cursor: wait; transform: none; }
.lg-btn .spinner { display: none; animation: lgSpin 0.7s linear infinite; }
.lg-btn.loading .btn-text,
.lg-btn.loading > .bi { display: none; }
.lg-btn.loading .spinner { display: inline-block; }
@keyframes lgSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
/* Shine sweep */
.lg-btn::after {
    content: ""; position: absolute; inset: 0; pointer-events: none;
    transform: translateX(-130%) skewX(-18deg);
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.45), transparent);
}
.lg-btn:hover::after { animation: lgShine 0.9s ease; }
@keyframes lgShine { to { transform: translateX(130%) skewX(-18deg); } }

/* ---- Register CTA ---- */
.lg-register {
    margin-top: 1.4rem; padding: 0.95rem 1rem; text-align: center;
    border-radius: 0.9rem;
    background: rgba(74, 69, 153, 0.16);
    border: 1px dashed rgba(148, 142, 255, 0.32);
}
.lg-register p { margin: 0 0 0.55rem; font-size: 0.83rem; color: var(--lg-muted); }
.lg-register a {
    display: inline-flex; align-items: center; gap: 0.45rem;
    font-size: 0.85rem; font-weight: 700; color: var(--lg-gold-2); text-decoration: none;
    transition: color 0.2s ease;
}
.lg-register a:hover { color: var(--lg-gold); text-decoration: none; }

/* ---- Trust strip ---- */
.lg-trust { display: flex; align-items: center; justify-content: center; gap: 0.5rem; flex-wrap: wrap; margin-top: 1.3rem; }
.lg-trust-item {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.32rem 0.7rem; border-radius: 999px;
    font-size: 0.7rem; font-weight: 600; color: var(--lg-muted);
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.lg-trust-item i { font-size: 0.72rem; color: var(--lg-gold); }

/* ---- Footer ---- */
.lg-foot {
    display: flex; align-items: center; justify-content: center; gap: 0.45rem;
    margin-top: 1.6rem; font-size: 0.75rem; color: var(--lg-dim);
}

/* ---- Entrance stagger ---- */
.lg-form .lg-field { animation: lgUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both; }
.lg-form .lg-field:nth-child(1) { animation-delay: 0.16s; }
.lg-form .lg-field:nth-child(2) { animation-delay: 0.24s; }
.lg-row    { animation: lgUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) 0.32s both; }
.lg-btn    { animation: lgUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) 0.4s both; }
.lg-register { animation: lgUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) 0.48s both; }
.lg-trust  { animation: lgUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) 0.56s both; }

@media (max-width: 480px) {
    .lg-card { padding: 1.7rem 1.25rem 1.5rem; border-radius: 1.25rem; }
    .lg-head h1 { font-size: 1.55rem; }
    .lg-wrap { padding-top: 4.5rem; }
}

@media (prefers-reduced-motion: reduce) {
    #lgParticles { display: none; }
    .lg-grid, .lg-blob, .lg-logo, .lg-card, .lg-logo-mark::after,
    .lg-form .lg-field, .lg-row, .lg-btn, .lg-register, .lg-trust { animation: none !important; }
}
</style>

<canvas id="lgParticles" aria-hidden="true"></canvas>

<div class="lg-bg">
    <div class="lg-grid"></div>
    <div class="lg-blob lg-blob-1"></div>
    <div class="lg-blob lg-blob-2"></div>
    <div class="lg-blob lg-blob-3"></div>
    <div class="lg-grain"></div>
    <div class="lg-vignette"></div>
</div>

<a href="{{ url('/') }}" class="lg-home"><i class="bi bi-arrow-left"></i> Back to store</a>

<div class="lg-wrap">
    <a href="{{ url('/') }}" class="lg-logo" aria-label="{{ storeName() }} home">
        <span class="lg-logo-mark"><i class="bi bi-currency-exchange"></i></span>
        <span class="lg-logo-name">{{ storeName() }}</span>
    </a>

    <div class="lg-card">
        <div class="lg-head">
            <span class="lg-badge"><i class="bi bi-shield-lock-fill"></i> Secure sign in</span>
            <h1>Welcome <em>back</em></h1>
            <p>Sign in to manage your plans, payments &amp; purchases.</p>
        </div>

        @if($errors->any())
        <div class="lg-alert" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="lg-form" id="lgForm">
            @csrf

            <div class="lg-field">
                <label for="email" class="lg-label">Email address</label>
                <div class="lg-input-wrap">
                    <i class="bi bi-envelope-fill lg-input-icon" aria-hidden="true"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="lg-input @error('email') is-invalid @enderror"
                        placeholder="you@example.com" required autocomplete="email" autofocus inputmode="email">
                </div>
                @error('email')
                    <p class="lg-error"><i class="bi bi-info-circle-fill"></i> {{ $message }}</p>
                @enderror
            </div>

            <div class="lg-field">
                <label for="password" class="lg-label">Password</label>
                <div class="lg-input-wrap">
                    <i class="bi bi-lock-fill lg-input-icon" aria-hidden="true"></i>
                    <input id="password" type="password" name="password"
                        class="lg-input lg-input-has-toggle @error('password') is-invalid @enderror"
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                        required autocomplete="current-password" spellcheck="false">
                    <button type="button" class="lg-toggle" id="lgToggle" aria-label="Show password">
                        <i class="bi bi-eye" id="lgIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="lg-error"><i class="bi bi-info-circle-fill"></i> {{ $message }}</p>
                @enderror
            </div>

            <div class="lg-row">
                <label class="lg-check" for="remember">
                    <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span class="lg-check-box"><i class="bi bi-check-lg"></i></span>
                    Remember me
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="lg-forgot"><i class="bi bi-key-fill"></i> Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="lg-btn" id="lgBtn">
                <i class="bi bi-box-arrow-in-right"></i>
                <span class="btn-text">Sign in to {{ storeName() }}</span>
                <i class="bi bi-arrow-repeat spinner"></i>
            </button>
        </form>

        <div class="lg-register">
            <p>New to {{ storeName() }}? Create an account in seconds.</p>
            <a href="{{ route('register') }}"><i class="bi bi-person-plus-fill"></i> Create free account</a>
        </div>

        <div class="lg-trust">
            <span class="lg-trust-item"><i class="bi bi-shield-fill-check"></i> Secured</span>
            <span class="lg-trust-item"><i class="bi bi-patch-check-fill"></i> Verified</span>
            <span class="lg-trust-item"><i class="bi bi-lock-fill"></i> Encrypted</span>
        </div>
    </div>

    <p class="lg-foot"><i class="bi bi-c-circle"></i> {{ date('Y') }} {{ storeName() }} &middot; Own at your own pace</p>
</div>

<script>
(function () {
    // Password visibility toggle
    var toggle = document.getElementById('lgToggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            var input = document.getElementById('password');
            var icon = document.getElementById('lgIcon');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    }

    // Loading state on submit
    var form = document.getElementById('lgForm');
    if (form) {
        form.addEventListener('submit', function () {
            var btn = document.getElementById('lgBtn');
            if (btn) { btn.classList.add('loading'); btn.disabled = true; }
        });
    }

    // Floating gold dust
    var canvas = document.getElementById('lgParticles');
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (canvas && !reduce) {
        var ctx = canvas.getContext('2d');
        var W, H, animId, parts = [];

        function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
        resize();
        window.addEventListener('resize', resize);

        for (var i = 0; i < 26; i++) {
            parts.push({
                x: Math.random() * W, y: Math.random() * H,
                s: Math.random() * 1.6 + 0.4,
                vx: (Math.random() - 0.5) * 0.28,
                vy: (Math.random() - 0.5) * 0.28,
                o: Math.random() * 0.3 + 0.08
            });
        }

        function draw() {
            ctx.clearRect(0, 0, W, H);
            for (var i = 0; i < parts.length; i++) {
                var p = parts[i];
                p.x += p.vx; p.y += p.vy;
                if (p.x < 0 || p.x > W) p.vx *= -1;
                if (p.y < 0 || p.y > H) p.vy *= -1;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.s, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(245,166,35,' + p.o + ')';
                ctx.fill();
            }
            animId = requestAnimationFrame(draw);
        }

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) { if (animId) cancelAnimationFrame(animId); }
            else { draw(); }
        });
        draw();
    }
})();
</script>
@endsection
