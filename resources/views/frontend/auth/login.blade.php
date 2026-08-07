@extends('frontend.auth_layout')
@section('body_class', 'auth-immersive')
@section('title', 'Sign In')

@section('content')
<style>
/* ============================================================
   {{ storeName() }} — Sign In (light split-panel redesign)
   Mirrors the storefront design system (paper + indigo + mango):
   a two-column card with a brand story panel on the left and the
   login form on the right. Cohesive with the rest of the site.
   ============================================================ */
:root {
    --sp-ink: #2e2a6b;          /* indigo — brand / trust  */
    --sp-ink-deep: #211e52;
    --sp-ink-soft: #4a4599;
    --sp-mango: #f5a623;        /* accent                  */
    --sp-mango-deep: #d98c0f;
    --sp-mango-soft: #ffd28a;
    --sp-mango-ink: #8a5800;
    --sp-paper: #f6f6f4;
    --sp-line: #e6e4de;
    --sp-muted: #5d6771;
    --sp-dim: #8b93a0;
    --sp-font-display: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
    --sp-font-body: "Inter", ui-sans-serif, system-ui, sans-serif;
    color-scheme: light;
}
::selection { background: rgba(245, 166, 35, 0.45); color: #2e2a6b; }
html, body { overflow-x: hidden; }

.sp-scroll {
    min-height: 100vh; min-height: 100dvh;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 1.5rem 1rem;
    background:
        radial-gradient(1000px 520px at 110% -10%, rgba(245, 166, 35, 0.18), transparent 60%),
        radial-gradient(820px 480px at -10% 110%, rgba(46, 42, 107, 0.14), transparent 60%),
        var(--sp-paper);
}

/* ---- Split panel ---- */
.sp-panel {
    display: grid; grid-template-columns: 0.95fr 1.05fr;
    width: 100%; max-width: 58rem;
    border-radius: 1.6rem; overflow: hidden;
    background: #fff;
    border: 1px solid rgba(46, 42, 107, 0.08);
    box-shadow: 0 30px 70px -30px rgba(46, 42, 107, 0.35);
    animation: spUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
}
@keyframes spUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }

/* ---- Brand (left) ---- */
.sp-brand {
    position: relative; overflow: hidden; isolation: isolate;
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 2.4rem 2.1rem;
    color: #fff;
    background: linear-gradient(160deg, #37327f 0%, var(--sp-ink-soft) 55%, var(--sp-ink-deep) 100%);
}
.sp-brand::before {
    content: ""; position: absolute; inset: 0; z-index: -1;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
    background-size: 34px 34px;
    -webkit-mask-image: radial-gradient(ellipse 90% 90% at 50% 18%, #000 28%, transparent 76%);
    mask-image: radial-gradient(ellipse 90% 90% at 50% 18%, #000 28%, transparent 76%);
}
.sp-brand::after {
    content: ""; position: absolute; z-index: -1; border-radius: 50%; filter: blur(60px); opacity: 0.55;
    width: 20rem; height: 20rem; right: -6rem; bottom: -7rem;
    background: radial-gradient(circle, var(--sp-mango), transparent 65%);
}
.sp-brand-logo { display: inline-flex; align-items: center; gap: 0.7rem; text-decoration: none; }
.sp-brand-logo:hover { text-decoration: none; }
.sp-brand-mark {
    display: flex; align-items: center; justify-content: center;
    width: 2.7rem; height: 2.7rem; border-radius: 0.85rem; font-size: 1.15rem; color: var(--sp-ink-deep);
    background: linear-gradient(135deg, var(--sp-mango-soft), var(--sp-mango));
    box-shadow: 0 8px 22px -6px rgba(245, 166, 35, 0.7);
}
.sp-brand-name { font-family: var(--sp-font-display); font-weight: 700; font-size: 1.1rem; }

.sp-brand-body { margin: 2.2rem 0 2.4rem; }
.sp-brand-eyebrow {
    display: inline-flex; align-items: center; gap: 0.4rem;
    font-size: 0.7rem; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--sp-mango-soft); margin-bottom: 0.85rem;
}
.sp-brand-body h2 {
    margin: 0 0 0.75rem; font-family: var(--sp-font-display);
    font-size: 1.9rem; font-weight: 700; letter-spacing: -0.02em; line-height: 1.15;
}
.sp-brand-body h2 em { font-style: normal; color: var(--sp-mango-soft); }
.sp-brand-body p { margin: 0; font-size: 0.92rem; line-height: 1.65; color: rgba(255, 255, 255, 0.78); }

.sp-features { display: grid; gap: 0.7rem; margin-top: 1.6rem; }
.sp-feature { display: flex; align-items: flex-start; gap: 0.6rem; font-size: 0.85rem; color: rgba(255, 255, 255, 0.88); }
.sp-feature i { color: var(--sp-mango-soft); font-size: 0.95rem; margin-top: 0.08rem; }

.sp-brand-quote {
    display: block; padding: 0.9rem 1rem; border-radius: 0.9rem;
    font-size: 0.8rem; line-height: 1.5; color: rgba(255, 255, 255, 0.85);
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.14);
}
.sp-brand-quote i { color: var(--sp-mango-soft); }

/* ---- Form (right) ---- */
.sp-form-side { padding: 2.6rem 2.4rem 2.2rem; display: flex; flex-direction: column; }
.sp-title { margin-bottom: 1.7rem; }
.sp-title h1 {
    margin: 0 0 0.45rem; font-family: var(--sp-font-display);
    font-size: 1.7rem; font-weight: 700; letter-spacing: -0.02em; color: var(--sp-ink);
}
.sp-title p { margin: 0; font-size: 0.9rem; color: var(--sp-muted); }

.sp-alert {
    display: flex; align-items: flex-start; gap: 0.55rem;
    margin-bottom: 1.3rem; padding: 0.8rem 0.95rem; border-radius: 0.8rem;
    font-size: 0.83rem; font-weight: 600; color: #a63d2f;
    background: #fdecea; border: 1px solid #f5c6c0;
}
.sp-alert i { margin-top: 0.15rem; }

.sp-form { display: flex; flex-direction: column; gap: 1.05rem; }
.sp-field { display: flex; flex-direction: column; gap: 0.4rem; }
.sp-label { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.04em; color: var(--sp-ink-soft); }

.sp-input-wrap { position: relative; }
.sp-input-icon {
    position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%);
    font-size: 0.95rem; color: var(--sp-dim); pointer-events: none; transition: color 0.18s ease;
}
.sp-input-wrap:focus-within .sp-input-icon { color: var(--sp-ink-soft); }
.sp-input {
    width: 100%; height: 3.05rem; padding: 0 0.9rem 0 2.55rem;
    border-radius: 0.75rem; font-size: 0.92rem; color: var(--sp-ink); outline: none;
    background: var(--sp-paper);
    border: 1.5px solid #ddd9cb;
    transition: border-color 0.18s ease, box-shadow 0.22s ease, background 0.18s ease;
}
.sp-input::placeholder { color: var(--sp-dim); }
.sp-input:hover { border-color: #c9c4ab; }
.sp-input:focus {
    border-color: var(--sp-ink-soft); background: #fff;
    box-shadow: 0 0 0 4px rgba(46, 42, 107, 0.1);
}
.sp-input:focus-visible { outline: 2px solid rgba(46, 42, 107, 0.7); outline-offset: 1px; }
.sp-input.is-invalid { border-color: #d66a5e; box-shadow: 0 0 0 4px rgba(214, 106, 94, 0.13); }
.sp-input-has-toggle { padding-right: 3rem; }
.sp-error { display: flex; align-items: center; gap: 0.35rem; font-size: 0.75rem; font-weight: 600; color: #c63f31; }

.sp-toggle {
    position: absolute; right: 0.4rem; top: 50%; transform: translateY(-50%);
    display: flex; align-items: center; justify-content: center;
    width: 2.4rem; height: 2.4rem; border-radius: 0.65rem;
    background: none; border: none; cursor: pointer; font-size: 1rem; color: var(--sp-dim);
    transition: color 0.18s ease, background 0.18s ease;
}
.sp-toggle:hover { color: var(--sp-ink-soft); background: rgba(46, 42, 107, 0.06); }

.sp-row { display: flex; align-items: center; justify-content: space-between; gap: 0.6rem 0.75rem; flex-wrap: wrap; }
.sp-check { display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none; font-size: 0.84rem; font-weight: 500; color: var(--sp-muted); }
.sp-check input { position: absolute; opacity: 0; width: 0; height: 0; }
.sp-check-box {
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    width: 1.15rem; height: 1.15rem; border-radius: 0.35rem;
    border: 1.5px solid #ccc7b4; background: #fff; transition: all 0.16s ease;
}
.sp-check-box i { font-size: 0.68rem; color: #fff; opacity: 0; transform: scale(0.4); transition: all 0.16s ease; }
.sp-check:hover .sp-check-box { border-color: var(--sp-ink-soft); }
.sp-check input:checked + .sp-check-box { background: var(--sp-ink-soft); border-color: var(--sp-ink-soft); }
.sp-check input:checked + .sp-check-box i { opacity: 1; transform: scale(1); }
.sp-forgot {
    display: inline-flex; align-items: center; gap: 0.35rem;
    font-size: 0.84rem; font-weight: 600; color: var(--sp-mango-ink); text-decoration: none;
    transition: color 0.18s ease;
}
.sp-forgot:hover { color: var(--sp-mango-deep); text-decoration: underline; text-underline-offset: 3px; }

.sp-btn {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    width: 100%; height: 3.2rem; margin-top: 0.35rem; border: none; border-radius: 0.8rem; cursor: pointer;
    font-family: var(--sp-font-display); font-size: 0.95rem; font-weight: 700; color: #fff;
    background: linear-gradient(135deg, var(--sp-ink-deep), var(--sp-ink-soft));
    box-shadow: 0 14px 30px -12px rgba(46, 42, 107, 0.65);
    transition: transform 0.18s ease, box-shadow 0.24s ease, filter 0.24s ease;
}
.sp-btn:hover { transform: translateY(-2px); filter: brightness(1.08); box-shadow: 0 20px 40px -14px rgba(46, 42, 107, 0.75); }
.sp-btn:active { transform: translateY(0) scale(0.99); }
.sp-btn:disabled { opacity: 0.75; cursor: wait; transform: none; }
.sp-btn .spinner { display: none; animation: spSpin 0.7s linear infinite; }
.sp-btn .btn-text { white-space: nowrap; }
.sp-btn.loading .btn-text, .sp-btn.loading > .bi:not(.spinner) { display: none; }
.sp-btn.loading .spinner { display: inline-block; }
@keyframes spSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.sp-btn::after {
    content: ""; position: absolute; inset: 0; pointer-events: none;
    transform: translateX(-130%) skewX(-18deg);
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
}
.sp-btn:hover::after { animation: spShine 0.9s ease; }
@keyframes spShine { to { transform: translateX(130%) skewX(-18deg); } }

.sp-register { margin-top: 1.35rem; text-align: center; font-size: 0.86rem; color: var(--sp-muted); }
.sp-register a { font-weight: 700; color: var(--sp-ink-soft); text-decoration: none; transition: color 0.18s ease; }
.sp-register a:hover { color: var(--sp-mango-deep); text-decoration: underline; text-underline-offset: 3px; }

.sp-trust {
    display: flex; align-items: center; justify-content: center; gap: 0.4rem 0.9rem; flex-wrap: wrap;
    margin-top: 1.25rem; padding-top: 1.15rem; border-top: 1.5px solid var(--sp-line);
}
.sp-trust-item { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.72rem; font-weight: 600; color: var(--sp-muted); }
.sp-trust-item i { color: var(--sp-mango-ink); }

.sp-foot { margin-top: 1.3rem; text-align: center; font-size: 0.7rem; color: var(--sp-dim); }

/* ---- Shared focus polish ---- */
.sp-toggle:focus-visible,
.sp-forgot:focus-visible,
.sp-register a:focus-visible,
.sp-btn:focus-visible,
.sp-brand-logo:focus-visible {
    outline: 3px solid rgba(245, 166, 35, 0.55); outline-offset: 2px;
}

@media (max-width: 760px) {
    .sp-panel { grid-template-columns: 1fr; max-width: 26.5rem; }
    .sp-brand { justify-content: flex-start; padding: 1.7rem 1.6rem 1.2rem; }
    .sp-brand-body { margin: 1.3rem 0 1rem; }
    .sp-brand-body h2 { font-size: 1.5rem; }
    .sp-features { display: none; }
    .sp-form-side { padding: 1.9rem 1.5rem 1.7rem; }
    .sp-scroll { padding: 1rem 0.75rem; }
}

@media (prefers-reduced-motion: reduce) {
    .sp-panel { animation: none; }
    .sp-btn:hover::after { animation: none; }
}
</style>

<div class="sp-scroll">
    <div class="sp-panel">

        <aside class="sp-brand">
            <a href="{{ url('/') }}" class="sp-brand-logo" aria-label="{{ storeName() }} home">
                <span class="sp-brand-mark"><i class="bi bi-currency-exchange"></i></span>
                <span class="sp-brand-name">{{ storeName() }}</span>
            </a>

            <div class="sp-brand-body">
                <span class="sp-brand-eyebrow"><i class="bi bi-shield-lock-fill"></i> Secure sign in</span>
                <h2>Own the <em>pace</em> of your money.</h2>
                <p>Log in to track your plans, manage payments and pick up exactly where you left off.</p>

                <div class="sp-features">
                    <span class="sp-feature"><i class="bi bi-check-circle-fill"></i> Personalised dashboards &amp; plans</span>
                    <span class="sp-feature"><i class="bi bi-check-circle-fill"></i> Payments tracked in real time</span>
                    <span class="sp-feature"><i class="bi bi-check-circle-fill"></i> Global support, every step</span>
                </div>
            </div>

            <span class="sp-brand-quote">
                <i class="bi bi-quote"></i> {{ storeName() }} made budgeting feel effortless — the progress plan alone pays for itself.
            </span>
        </aside>

        <section class="sp-form-side">
            <header class="sp-title">
                <h1>Sign in</h1>
                <p>Enter your details to access your account.</p>
            </header>

            @if($errors->any())
            <div class="sp-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="sp-form" id="spForm">
                @csrf

                <div class="sp-field">
                    <label for="email" class="sp-label">Email address</label>
                    <div class="sp-input-wrap">
                        <i class="bi bi-envelope-fill sp-input-icon" aria-hidden="true"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="sp-input @error('email') is-invalid @enderror"
                            placeholder="you@example.com" required autocomplete="email" autofocus inputmode="email">
                    </div>
                    @error('email')
                        <p class="sp-error"><i class="bi bi-info-circle-fill"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="sp-field">
                    <label for="password" class="sp-label">Password</label>
                    <div class="sp-input-wrap">
                        <i class="bi bi-lock-fill sp-input-icon" aria-hidden="true"></i>
                        <input id="password" type="password" name="password"
                            class="sp-input sp-input-has-toggle @error('password') is-invalid @enderror"
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                            required autocomplete="current-password" spellcheck="false">
                        <button type="button" class="sp-toggle" id="spToggle" aria-label="Show password">
                            <i class="bi bi-eye" id="spIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="sp-error"><i class="bi bi-info-circle-fill"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="sp-row">
                    <label class="sp-check" for="remember">
                        <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span class="sp-check-box"><i class="bi bi-check-lg"></i></span>
                        Remember me
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="sp-forgot"><i class="bi bi-key-fill"></i> Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="sp-btn" id="spBtn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="btn-text">Sign in to {{ storeName() }}</span>
                    <i class="bi bi-arrow-repeat spinner"></i>
                </button>
            </form>

            <p class="sp-register">
                New to {{ storeName() }}?
                <a href="{{ route('register') }}"><i class="bi bi-person-plus-fill"></i> Create free account</a>
            </p>

            <div class="sp-trust">
                <span class="sp-trust-item"><i class="bi bi-shield-fill-check"></i> Secured</span>
                <span class="sp-trust-item"><i class="bi bi-patch-check-fill"></i> Verified</span>
                <span class="sp-trust-item"><i class="bi bi-lock-fill"></i> Encrypted</span>
            </div>
        </section>
    </div>

    <p class="sp-foot"><i class="bi bi-c-circle"></i> {{ date('Y') }} {{ storeName() }} - Own at your own pace</p>
</div>

<script>
(function () {
    var toggle = document.getElementById('spToggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            var input = document.getElementById('password');
            var icon = document.getElementById('spIcon');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    }

    var form = document.getElementById('spForm');
    if (form) {
        form.addEventListener('submit', function () {
            var btn = document.getElementById('spBtn');
            if (btn) { btn.classList.add('loading'); btn.disabled = true; }
        });
    }
})();
</script>
@endsection