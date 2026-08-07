@extends('frontend.auth_layout')
@section('body_class', 'auth-immersive')
@section('title', 'Sign In')

@section('content')
<style>
/* ============================================================
   {{ storeName() }} — Sign In (dark editorial premium)
   Near-black stage, split editorial layout: a brand narrative with
   a live stats rail on the left, a glassy neon-edged form card on
   the right. Charcoal + champagne gold + electric cyan accents.
   ============================================================ */
:root {
    --dp-bg: #0c0c11;
    --dp-panel: #101016;
    --dp-panel-2: #15151d;
    --dp-line: rgba(255, 255, 255, 0.09);
    --dp-line-2: rgba(255, 255, 255, 0.14);
    --dp-gold: #f3c15a;
    --dp-gold-2: #ffd88f;
    --dp-cyan: #56c7f4;
    --dp-violet: #8c7bff;
    --dp-ink: #ededf1;
    --dp-muted: #a49fb5;
    --dp-dim: #6f6a7e;
    --dp-danger: #ff7a6e;
    --dp-font-display: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
    --dp-font-body: "Inter", ui-sans-serif, system-ui, sans-serif;
    --dp-font-mono: "IBM Plex Mono", ui-monospace, monospace;
    color-scheme: dark;
}
::selection { background: rgba(243, 193, 90, 0.4); color: #fff; }
html, body { overflow-x: hidden; }
body { scrollbar-color: rgba(140, 123, 255, 0.4) rgba(255, 255, 255, 0.05); }

/* ---- Scene ---- */
.dp-scene {
    position: relative; min-height: 100vh; min-height: 100dvh;
    display: flex; align-items: center;
    padding: 2rem clamp(1rem, 4vw, 4rem);
    background:
        radial-gradient(1100px 620px at 92% -12%, rgba(124, 105, 255, 0.22), transparent 60%),
        radial-gradient(900px 520px at -8% 112%, rgba(243, 193, 90, 0.10), transparent 55%),
        linear-gradient(180deg, var(--dp-bg), #0a0a0f);
}
.dp-gridline {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
    background-size: 56px 56px;
    -webkit-mask-image: radial-gradient(ellipse 80% 80% at 50% 30%, #000 20%, transparent 78%);
    mask-image: radial-gradient(ellipse 80% 80% at 50% 30%, #000 20%, transparent 78%);
}
.dp-orb {
    position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.34; pointer-events: none;
    animation: dpFloat 14s ease-in-out infinite alternate;
}
.dp-orb-1 { width: 26rem; height: 26rem; top: -8rem; right: -6rem; background: radial-gradient(circle, var(--dp-violet), transparent 65%); }
.dp-orb-2 { width: 22rem; height: 22rem; bottom: -9rem; left: 4%; background: radial-gradient(circle, rgba(243, 193, 90, 0.5), transparent 65%); animation-delay: -6s; }
@keyframes dpFloat { from { transform: translateY(0); } to { transform: translateY(36px); } }

/* ---- Shell ---- */
.dp-shell {
    position: relative; z-index: 2;
    width: 100%; max-width: 70rem; margin-inline: auto;
    display: grid; grid-template-columns: 1.1fr 1fr; gap: clamp(1.5rem, 4vw, 4rem);
    align-items: center;
}

/* ---- Left: manifesto ---- */
.dp-brand { padding: 1rem 0; }
.dp-brand-logo { display: inline-flex; align-items: center; gap: 0.7rem; text-decoration: none; margin-bottom: 2.4rem; }
.dp-brand-logo:hover { text-decoration: none; }
.dp-brand-mark {
    position: relative; display: flex; align-items: center; justify-content: center;
    width: 2.6rem; height: 2.6rem; border-radius: 0.8rem; font-size: 1.1rem; color: #16131c;
    background: linear-gradient(135deg, var(--dp-gold-2), var(--dp-gold));
    box-shadow: 0 10px 26px -8px rgba(243, 193, 90, 0.75);
}
.dp-brand-mark::after {
    content: ""; position: absolute; inset: -3px; border-radius: 1rem;
    border: 1px solid rgba(243, 193, 90, 0.45);
    animation: dpMark 3s ease-in-out infinite;
}
@keyframes dpMark { 0%, 100% { opacity: 0.5; transform: scale(1); } 50% { opacity: 0; transform: scale(1.25); } }
.dp-brand-name { font-family: var(--dp-font-display); font-size: 1.15rem; font-weight: 700; color: var(--dp-ink); }

.dp-eyebrow {
    display: inline-flex; align-items: center; gap: 0.45rem;
    font-family: var(--dp-font-mono); font-size: 0.72rem; font-weight: 500;
    letter-spacing: 0.18em; text-transform: uppercase; color: var(--dp-gold);
    margin-bottom: 1.1rem;
}
.dp-eyebrow::before { content: ""; width: 1.6rem; height: 1px; background: var(--dp-gold); opacity: 0.7; }

.dp-brand h1 {
    margin: 0 0 1rem;
    font-family: var(--dp-font-display);
    font-size: clamp(2.1rem, 4.5vw, 3.4rem);
    font-weight: 700; letter-spacing: -0.03em; line-height: 1.04; color: var(--dp-ink);
}
.dp-brand h1 .dp-accent-c { display: inline-block; }
.dp-brand h1 .dp-accent-c b { font-weight: 700; }
.dp-grad {
    font-style: normal;
    background: linear-gradient(92deg, var(--dp-gold-2) 0%, var(--dp-gold) 30%, #fff 100%);
    -webkit-background-clip: text; background-clip: text; color: transparent;
}
.dp-brand-sub { margin: 0; max-width: 30rem; font-size: 0.98rem; line-height: 1.7; color: var(--dp-muted); }

/* ---- Stats rail ---- */
.dp-stats {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;
    margin-top: 2rem; padding: 1rem;
    border: 1px solid var(--dp-line); border-radius: 1rem;
    background: rgba(255, 255, 255, 0.03);
    -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px);
}
.dp-stat { display: flex; flex-direction: column; gap: 0.3rem; }
.dp-stat-label { font-family: var(--dp-font-mono); font-size: 0.62rem; letter-spacing: 0.14em; text-transform: uppercase; color: var(--dp-fg2, var(--dp-muted)); }
.dp-stat-value { font-family: var(--dp-font-display); font-size: 1.35rem; font-weight: 700; color: var(--dp-ink); font-variant-numeric: tabular-nums; }
.dp-stat-value small { font-size: 0.8rem; font-weight: 500; color: var(--dp-fg, var(--dp-muted)); }
.dp-stat-violet .dp-stat-value { color: var(--dp-violet); }

/* ---- Feature chips ---- */
.dp-chips { display: flex; flex-wrap: wrap; gap: 0.6rem; margin-top: 1.4rem; }
.dp-chip {
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.5rem 0.85rem; border-radius: 999px;
    font-size: 0.78rem; font-weight: 600; color: var(--dp-muted);
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid var(--dp-line);
    transition: color 0.18s ease, border-color 0.18s ease;
}
.dp-chip i { color: var(--dp-gold); }
.dp-chip:hover { color: var(--dp-ink); border-color: var(--dp-line-2); }

/* ---- Right: form card ---- */
.dp-card {
    position: relative; overflow: hidden;
    padding: 2.3rem 2.1rem 1.9rem;
    border-radius: 1.4rem;
    background: var(--dp-panel);
    border: 1px solid var(--dp-line-2);
    box-shadow: 0 40px 90px -40px rgba(0, 0, 0, 0.9);
    animation: dpUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
}
@keyframes dpUp { from { opacity: 0; transform: translateY(26px); } to { opacity: 1; transform: translateY(0); } }

/* Gradient ring */
.dp-card::before {
    content: ""; position: absolute; inset: 0; border-radius: inherit; padding: 1px;
    background: linear-gradient(160deg, rgba(243, 193, 90, 0.55), rgba(124, 105, 255, 0.35) 45%, transparent 70%);
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
}
/* Top glow */
.dp-card::after {
    content: ""; position: absolute; top: -6rem; left: 50%; transform: translateX(-50%);
    width: 22rem; height: 10rem; border-radius: 50%; pointer-events: none;
    background: radial-gradient(circle, rgba(124, 105, 255, 0.22), transparent 70%);
}

.dp-card-head { text-align: center; margin-bottom: 1.6rem; position: relative; }
.dp-card-badge {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.32rem 0.8rem; border-radius: 999px;
    font-size: 0.66rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase;
    color: var(--dp-gold-2); background: rgba(243, 193, 90, 0.1);
    border: 1px solid rgba(243, 193, 90, 0.28);
}
.dp-card-head h2 {
    margin: 0.9rem 0 0.35rem; font-family: var(--dp-font-display);
    font-size: 1.55rem; font-weight: 700; letter-spacing: -0.02em; color: var(--dp-ink);
}
.dp-card-head p { margin: 0; font-size: 0.84rem; color: var(--dp-muted); }

.dp-alert {
    display: flex; align-items: flex-start; gap: 0.55rem; position: relative;
    margin-bottom: 1.3rem; padding: 0.85rem 0.95rem; border-radius: 0.8rem;
    font-size: 0.82rem; font-weight: 600; color: #ffb0a8;
    background: rgba(255, 122, 110, 0.1); border: 1px solid rgba(255, 122, 110, 0.32);
}

.dp-form { display: flex; flex-direction: column; gap: 1.1rem; position: relative; }
.dp-field { display: flex; flex-direction: column; gap: 0.42rem; }
.dp-label { font-family: var(--dp-font-mono); font-size: 0.68rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--dp-muted); }

.dp-input-wrap { position: relative; }
.dp-input-icon {
    position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
    font-size: 0.95rem; color: var(--dp-f6, var(--dp-faint, var(--dp-muted))); pointer-events: none; transition: color 0.18s ease;
}
.dp-input-wrap:focus-within .dp-input-icon { color: var(--dp-gold); }
.dp-input {
    width: 100%; height: 3.15rem; padding: 0 1rem 0 2.7rem;
    border-radius: 0.8rem; font-size: 0.92rem; color: var(--dp-ink); outline: none;
    background: rgba(255, 255, 255, 0.035);
    border: 1.5px solid var(--dp-line-2);
    transition: border-color 0.18s ease, box-shadow 0.22s ease, background 0.18s ease;
}
.dp-input-has-toggle { padding-right: 3.1rem; }
.dp-input::placeholder { color: var(--dp-f6, var(--dp-muted)); }
.dp-input:hover { border-color: rgba(255, 255, 255, 0.24); }
.dp-input:focus {
    border-color: rgba(243, 193, 90, 0.8); background: rgba(255, 255, 255, 0.05);
    box-shadow: 0 0 0 4px rgba(243, 193, 90, 0.14);
}
.dp-input:focus-visible { outline: none; }
.dp-input.is-invalid { border-color: rgba(255, 122, 110, 0.65); box-shadow: 0 0 0 4px rgba(255, 122, 110, 0.13); }
.dp-input:-webkit-autofill,
.dp-input:-webkit-autofill:hover,
.dp-input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #17141f inset;
    -webkit-text-fill-color: var(--dp-ink);
    caret-color: var(--dp-ink);
    transition: background-color 9999s ease-in-out 0s;
}
.dp-error { display: flex; align-items: center; gap: 0.35rem; font-size: 0.74rem; font-weight: 600; color: var(--dp-danger); }
.dp-toggle {
    position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%);
    display: flex; align-items: center; justify-content: center;
    width: 2.4rem; height: 2.4rem; border-radius: 0.7rem;
    background: none; border: none; cursor: pointer; font-size: 1rem; color: var(--dp-muted);
    transition: color 0.18s ease, background 0.18s ease;
}
.dp-toggle:hover { color: var(--dp-gold); background: rgba(243, 193, 90, 0.1); }

.dp-row { display: flex; align-items: center; justify-content: space-between; gap: 0.6rem 0.75rem; flex-wrap: wrap; }
.dp-check { display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none; font-size: 0.82rem; font-weight: 500; color: var(--dp-muted); }
.dp-check input { position: absolute; opacity: 0; width: 0; height: 0; }
.dp-check-box {
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    width: 1.15rem; height: 1.15rem; border-radius: 0.35rem;
    border: 1.5px solid rgba(255, 255, 255, 0.24); background: rgba(255, 255, 255, 0.04); transition: all 0.16s ease;
}
.dp-check-box i { font-size: 0.68rem; color: #16131c; opacity: 0; transform: scale(0.4); transition: all 0.16s ease; }
.dp-check:hover .dp-check-box { border-color: var(--dp-gold); }
.dp-check input:checked + .dp-check-box { background: var(--dp-gold); border-color: var(--dp-gold); }
.dp-check input:checked + .dp-check-box i { opacity: 1; transform: scale(1); }
.dp-check input:focus-visible + .dp-check-box { outline: 3px solid rgba(243, 193, 90, 0.55); outline-offset: 2px; }
.dp-forgot {
    display: inline-flex; align-items: center; gap: 0.35rem;
    font-size: 0.82rem; font-weight: 600; color: var(--dp-gold); text-decoration: none; transition: color 0.18s ease;
}
.dp-forgot:hover { color: var(--dp-gold-2); text-decoration: underline; text-underline-offset: 3px; }

.dp-btn {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    width: 100%; height: 3.2rem; margin-top: 0.35rem; border: none; border-radius: 0.8rem; cursor: pointer;
    font-family: var(--dp-font-display); font-size: 0.96rem; font-weight: 700; letter-spacing: 0.01em; color: #16131c;
    background: linear-gradient(135deg, var(--dp-gold-2) 0%, var(--dp-gold) 55%, #e6a93c 100%);
    box-shadow: 0 16px 38px -14px rgba(243, 193, 90, 0.7);
    transition: transform 0.18s ease, box-shadow 0.24s ease, filter 0.24s ease;
}
.dp-btn:hover { transform: translateY(-2px); filter: brightness(1.05); box-shadow: 0 22px 48px -16px rgba(243, 193, 90, 0.85); }
.dp-btn:active { transform: translateY(0) scale(0.99); }
.dp-btn:disabled { opacity: 0.75; cursor: wait; transform: none; }
.dp-btn .spinner { display: none; animation: dpSpin 0.7s linear infinite; }
.dp-btn .btn-text { white-space: nowrap; }
.dp-btn.loading .btn-text, .dp-btn.loading > .bi:not(.spinner) { display: none; }
.dp-btn.loading .spinner { display: inline-block; }
@keyframes dpSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.dp-btn::after {
    content: ""; position: absolute; inset: 0; pointer-events: none;
    transform: translateX(-130%) skewX(-18deg);
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
}
.dp-btn:hover::after { animation: dpShine 0.9s ease; }
@keyframes dpShine { to { transform: translateX(130%) skewX(-18deg); } }

.dp-register { margin-top: 1.3rem; text-align: center; }
.dp-register p { margin: 0 0 0.35rem; font-size: 0.82rem; color: var(--dp-muted); }
.dp-register a { font-size: 0.86rem; font-weight: 700; color: var(--dp-gold-2); text-decoration: none; transition: color 0.18s ease; }
.dp-register a:hover { color: var(--dp-gold); text-decoration: none; }

.dp-trust { display: flex; align-items: center; justify-content: center; gap: 0.5rem 1rem; flex-wrap: wrap; margin-top: 1.1rem; padding-top: 1.1rem; border-top: 1px solid var(--dp-line); }
.dp-trust-item { display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.7rem; font-weight: 600; color: var(--dp-faint, var(--dp-muted)); }
.dp-trust-item i { color: var(--dp-gold); }

/* ---- Focus polish ---- */
.dp-toggle:focus-visible,
.dp-forgot:focus-visible,
.dp-register a:focus-visible,
.dp-btn:focus-visible,
.dp-brand-logo:focus-visible {
    outline: 3px solid rgba(243, 193, 90, 0.55); outline-offset: 2px;
}

/* ---- Responsive ---- */
@media (max-width: 880px) {
    .dp-shell { grid-template-columns: 1fr; gap: 1.5rem; }
    .dp-brand { animation: none; }
    .dp-brand h1 { font-size: clamp(1.9rem, 6vw, 2.5rem); }
    .dp-card { max-width: 26.5rem; margin-inline: auto; width: 100%; }
    .dp-stats { margin-top: 1.5rem; }
}

@media (prefers-reduced-motion: reduce) {
    .dp-orb, .dp-brand-mark::after { animation: none; }
    .dp-card { animation: none; }
}
</style>

<div class="dp-scene">
    <div class="dp-gridline" aria-hidden="true"></div>
    <div class="dp-orb dp-orb-1" aria-hidden="true"></div>
    <div class="dp-orb dp-orb-2" aria-hidden="true"></div>

    <div class="dp-shell">

        <!-- Left: manifesto -->
        <div class="dp-brand">
            <a href="{{ url('/') }}" class="dp-brand-logo" aria-label="{{ storeName() }} home">
                <span class="dp-brand-mark"><i class="bi bi-currency-exchange"></i></span>
                <span class="dp-brand-name">{{ storeName() }}</span>
            </a>

            <span class="dp-eyebrow">Own at your own pace</span>

            <h1>
                Turn <span class="dp-accent-c"><b class="dp-grad">plans</b></span>
                into
                <span class="dp-accent-c"><b class="dp-grad">payoffs.</b></span>
            </h1>
            <p class="dp-brand-sub">
                Sign in to bring your budget, savings and payment plans
                together in one calm, beautifully-tuned dashboard.
            </p>

            <div class="dp-stats" id="dpStats">
                <div class="dp-stat">
                    <span class="dp-stat-label">On track</span>
                    <span class="dp-stat-value"><span id="dpStatOnTrack">94</span><small>%</small></span>
                </div>
                <div class="dp-stat dp-stat-violet">
                    <span class="dp-stat-label">Goals hit</span>
                    <span class="dp-stat-value"><span id="dpStatGoals">1.<span id="dpStatGoalsDec">2</span></span><small>k</small></span>
                </div>
                <div class="dp-stat">
                    <span class="dp-stat-label">Members</span>
                    <span class="dp-stat-value"><span id="dpStatMembers">38</span><small>k</small></span>
                </div>
            </div>

            <div class="dp-chips">
                <span class="dp-chip"><i class="bi bi-graph-up-arrow"></i> Progress plans</span>
                <span class="dp-chip"><i class="bi bi-shield-lock-fill"></i> Bank-grade security</span>
                <span class="dp-chip"><i class="bi bi-headset"></i> 24/7 support</span>
            </div>
        </div>

        <!-- Right: form -->
        <div class="dp-card">
            <header class="dp-card-head">
                <span class="dp-card-badge"><i class="bi bi-shield-lock-fill"></i> Secure sign in</span>
                <h2>Welcome <em class="dp-grad">back</em></h2>
                <p>Enter your details below to get straight in.</p>
            </header>

            @if($errors->any())
            <div class="dp-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="dp-form" id="dpForm">
                @csrf

                <div class="dp-field">
                    <label for="email" class="dp-label">Email address</label>
                    <div class="dp-input-wrap">
                        <i class="bi bi-envelope-fill dp-input-icon" aria-hidden="true"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="dp-input @error('email') is-invalid @enderror"
                            placeholder="you@example.com" required autocomplete="email" autofocus inputmode="email">
                    </div>
                    @error('email')
                        <p class="dp-error"><i class="bi bi-info-circle-fill"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="dp-field">
                    <label for="password" class="dp-label">Password</label>
                    <div class="dp-input-wrap">
                        <i class="bi bi-lock-fill dp-input-icon" aria-hidden="true"></i>
                        <input id="password" type="password" name="password"
                            class="dp-input dp-input-has-toggle @error('password') is-invalid @enderror"
                            placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                            required autocomplete="current-password" spellcheck="false">
                        <button type="button" class="dp-toggle" id="dpToggle" aria-label="Show password">
                            <i class="bi bi-eye" id="dpIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="dp-error"><i class="bi bi-info-circle-fill"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="dp-row">
                    <label class="dp-check" for="remember">
                        <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span class="dp-check-box"><i class="bi bi-check-lg"></i></span>
                        Remember me
                    </label>
                    @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="dp-forgot"><i class="bi bi-key-fill"></i> Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="dp-btn" id="dpBtn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="btn-text">Sign in to {{ storeName() }}</span>
                    <i class="bi bi-arrow-repeat spinner"></i>
                </button>
            </form>

            <div class="dp-register">
                <p>New to {{ storeName() }}?</p>
                <a href="{{ route('register') }}"><i class="bi bi-person-plus-fill"></i> Create free account</a>
            </div>

            <div class="dp-trust">
                <span class="dp-trust-item"><i class="bi bi-shield-fill-check"></i> Secured</span>
                <span class="dp-trust-item"><i class="bi bi-patch-check-fill"></i> Verified</span>
                <span class="dp-trust-item"><i class="bi bi-lock-fill"></i> Encrypted</span>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var toggle = document.getElementById('dpToggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            var input = document.getElementById('password');
            var icon = document.getElementById('dpIcon');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    }

    var form = document.getElementById('dpForm');
    if (form) {
        form.addEventListener('submit', function () {
            var btn = document.getElementById('dpBtn');
            if (btn) { btn.classList.add('loading'); btn.disabled = true; }
        });
    }

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var el1 = document.getElementById('dpStatOnTrack');
    var elG = document.getElementById('dpStatGoalsDec') || document.getElementById('dpStatGoals');
    var elM = document.getElementById('dpStatMembers');
    var vals = { a: 94, b: 1.2, c: 38 };
    if (el1 && elG && elM && !reduce) {
        var tick = function () {
            vals.a = vals.a >= 98 ? 92 : vals.a + (Math.random() > 0.5 ? 1 : -1);
            vals.b = Math.max(1.0, Math.min(1.9, +(vals.b + ((Math.random() - 0.5) * 0.1)).toFixed(1)));
            vals.c = vals.c >= 42 ? 36 + Math.floor(Math.random() * 4) : (vals.c + Math.floor(Math.random() * 3));
            el1.textContent = vals.a;
            elG.textContent = vals.b.toFixed(1);
            elM.textContent = vals.c;
        };
        tick();
        setInterval(tick, 2400);
    }
})();
</script>
@endsection