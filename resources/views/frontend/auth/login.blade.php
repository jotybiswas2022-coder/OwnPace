@extends('frontend.auth_layout')
@section('body_class', 'auth-immersive')
@section('title', 'Sign In')

@section('content')
<style>
/* ============================================================
   {{ storeName() }} — Sign In (centered dark luxury card)
   One calm, centered glass card on a near-black stage with soft
   glows. Gold + ink palette, spacious and focused.
   ============================================================ */
:root {
    --cc-bg: #0b0b12;
    --cc-panel: #12121a;
    --cc-line: rgba(255, 255, 255, 0.1);
    --cc-line-2: rgba(255, 255, 255, 0.16);
    --cc-gold: #f3c15a;
    --cc-gold-2: #ffd88f;
    --cc-violet: #8c7bff;
    --cc-ink: #ededf1;
    --cc-muted: #a6a1b5;
    --cc-dim: #6f6a7e;
    --cc-danger: #ff7a6e;
    --cc-font-display: "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
    --cc-font-body: "Inter", ui-sans-serif, system-ui, sans-serif;
    --cc-font-mono: "IBM Plex Mono", ui-monospace, monospace;
    color-scheme: dark;
}
::selection{ background: rgba(243,193,90,0.4); color: #fff; }
html, body{ overflow-x: hidden; }
body{ scrollbar-color: rgba(140,123,255,0.4) rgba(255,255,255,0.05); }

/* ---- Stage ---- */
.cc-stage{
    position: relative; min-height: 100vh; min-height: 100dvh;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 3rem 1rem 2rem;
    background:
        radial-gradient(900px 560px at 85% -12%, rgba(124,105,255,0.2), transparent 60%),
        radial-gradient(760px 500px at 0% 110%, rgba(243,193,90,0.1), transparent 55%),
        linear-gradient(180deg, var(--cc-bg), #08080d);
}
.cc-gridline{
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 52px 52px;
    -webkit-mask-image: radial-gradient(ellipse 70% 70% at 50% 40%, #000 20%, transparent 78%);
    mask-image: radial-gradient(ellipse 70% 70% at 50% 40%, #000 20%, transparent 78%);
}

/* ---- Back link ---- */
.cc-back{
    position: fixed; top: 1.1rem; left: 1.1rem; z-index: 3;
    display: inline-flex; align-items: center; gap: 0.45rem;
    padding: 0.5rem 0.9rem; border-radius: 999px;
    font-size: 0.78rem; font-weight: 600; color: var(--cc-muted); text-decoration: none;
    background: rgba(255,255,255,0.05);
    -webkit-backdrop-filter: blur(12px); backdrop-filter: blur(12px);
    border: 1px solid var(--cc-line);
    transition: color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
}
.cc-back:hover{
    color: var(--cc-gold-2); text-decoration: none; border-color: rgba(243,193,90,0.5); transform: translateY(-1px);
}
.cc-back:focus-visible{ outline: 3px solid rgba(243,193,90,0.55); outline-offset: 2px; }

/* ---- Card ---- */
.cc-card{
    position: relative; overflow: hidden;
    width: 100%; max-width: 26rem;
    padding: 2.6rem 2.2rem 1.9rem;
    border-radius: 1.5rem;
    background: var(--cc-panel);
    -webkit-backdrop-filter: blur(20px); backdrop-filter: blur(20px);
    border: 1px solid var(--cc-line-2);
    box-shadow: 0 40px 100px -40px rgba(0,0,0,0.9);
    animation: ccUp 0.6s cubic-bezier(0.22,1,0.36,1) both;
}
/* gradient ring */
.cc-card::before{
    content: ""; position: absolute; inset: 0; border-radius: inherit; padding: 1px;
    background: linear-gradient(160deg, rgba(243,193,90,0.5), rgba(124,105,255,0.32) 45%, transparent 72%);
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
}
/* top glow */
.cc-card::after{
    content: ""; position: absolute; top: -6rem; left: 50%; transform: translateX(-50%);
    width: 20rem; height: 10rem; border-radius: 50%; pointer-events: none;
    background: radial-gradient(circle, rgba(124,105,255,0.2), transparent 70%);
}
@keyframes ccUp{ from{ opacity:0; transform: translateY(28px) scale(0.99); } to{ opacity:1; transform: translateY(0) scale(1); } }

/* ---- Centered gold dust inside the box ---- */
.cc-dust{ position: absolute; inset: 0; pointer-events: none; z-index: 0; }
.cc-dust i{
    position: absolute; bottom: -6px; border-radius: 50%;
    background: radial-gradient(circle, var(--cc-gold-2), var(--cc-gold));
    opacity: 0; animation: ccDust var(--d, 7s) linear var(--dl, 0s) infinite;
}
.cc-dust .d1{ left: 18%; --d: 8s;  --dl: 0.2s; width: 3px; height: 3px; }
.cc-dust .d2{ left: 34%; --d: 10s; --dl: 2.4s; width: 2px; height: 2px; }
.cc-dust .d3{ left: 50%; --d: 9s;  --dl: 1s;   width: 3px; height: 3px; }
.cc-dust .d4{ left: 62%; --d: 11s; --dl: 3.4s; width: 2px; height: 2px; }
.cc-dust .d5{ left: 78%; --d: 8.5s; --dl: 1.8s; width: 3px; height: 3px; }
.cc-dust .d6{ left: 28%; --d: 12s; --dl: 4.5s; width: 2px; height: 2px; }
.cc-dust .d7{ left: 72%; --d: 9.5s; --dl: 0.9s; width: 2px; height: 2px; }
@keyframes ccDust{
    0%   { transform: translateY(0) scale(0.6); opacity: 0; }
    12%  { opacity: 0.9; }
    85%  { opacity: 0.25; }
    100% { transform: translateY(-330px) scale(1); opacity: 0; }
}

/* ---- Header ---- */
.cc-head{ text-align: center; position: relative; margin-bottom: 1.7rem; }
.cc-mark{
    position: relative; display: inline-flex; align-items: center; justify-content: center;
    width: 3.3rem; height: 3.3rem; border-radius: 1rem; font-size: 1.35rem; color: #16131c;
    background: linear-gradient(135deg, var(--cc-gold-2), var(--cc-gold));
    box-shadow: 0 12px 32px -10px rgba(243,193,90,0.75);
    margin-bottom: 1.1rem;
}
.cc-mark::after{
    content: ""; position: absolute; inset: -4px; border-radius: 1.25rem;
    border: 1px solid rgba(243,193,90,0.4);
    animation: ccMark 3s ease-in-out infinite;
}
@keyframes ccMark{ 0%,100%{ opacity:0.5; transform: scale(1); } 50%{ opacity:0; transform: scale(1.22); } }
.cc-head h2{
    margin: 0 0 0.45rem; font-family: var(--cc-font-display);
    font-size: 1.7rem; font-weight: 700; letter-spacing: -0.02em; color: var(--cc-ink);
}
.cc-head h2 span{
    font-style: normal;
    background: linear-gradient(92deg, var(--cc-gold-2), var(--cc-gold));
    -webkit-background-clip: text; background-clip: text; color: transparent;
}
.cc-head p{ margin: 0; font-size: 0.86rem; color: var(--cc-muted); }

/* ---- Alert ---- */
.cc-alert{
    display: flex; align-items: flex-start; gap: 0.55rem; position: relative;
    margin-bottom: 1.3rem; padding: 0.85rem 0.95rem; border-radius: 0.8rem;
    font-size: 0.82rem; font-weight: 600; color: #ffb0a8;
    background: rgba(255,122,110,0.1); border: 1px solid rgba(255,122,110,0.32);
}

/* ---- Form ---- */
.cc-form{ display: flex; flex-direction: column; gap: 1.05rem; position: relative; text-align: center; }
.cc-field{ display: flex; flex-direction: column; gap: 0.42rem; }
.cc-label{ font-family: var(--cc-font-mono); font-size: 0.66rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--cc-muted); text-align: center; }

.cc-input-wrap{ position: relative; }
.cc-input-icon{
    position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
    font-size: 0.95rem; color: var(--cc-dim); pointer-events: none; transition: color 0.18s ease;
}
.cc-input-wrap:focus-within .cc-input-icon{ color: var(--cc-gold); }
.cc-input{
    width: 100%; height: 3.15rem; padding: 0 1rem 0 2.7rem;
    border-radius: 0.8rem; font-size: 0.92rem; color: var(--cc-ink); outline: none;
    background: rgba(255,255,255,0.035);
    border: 1.5px solid var(--cc-line-2);
    transition: border-color 0.18s ease, box-shadow 0.22s ease, background 0.18s ease;
}
.cc-input-has-toggle{ padding-right: 3.1rem; }
.cc-input::placeholder{ color: var(--cc-dim); }
.cc-input:hover{ border-color: rgba(255,255,255,0.24); }
.cc-input:focus{
    border-color: rgba(243,193,90,0.8); background: rgba(255,255,255,0.05);
    box-shadow: 0 0 0 4px rgba(243,193,90,0.14);
}
.cc-input:focus-visible{ outline: none; }
.cc-input.is-invalid{ border-color: rgba(255,122,110,0.65); box-shadow: 0 0 0 4px rgba(255,122,110,0.13); }
.cc-input:-webkit-autofill,
.cc-input:-webkit-autofill:hover,
.cc-input:-webkit-autofill:focus{
    -webkit-box-shadow: 0 0 0 1000px #17141f inset;
    -webkit-text-fill-color: var(--cc-ink); caret-color: var(--cc-ink);
    transition: background-color 9999s ease-in-out 0s;
}
.cc-error{ display: flex; align-items: center; gap: 0.35rem; font-size: 0.74rem; font-weight: 600; color: var(--cc-danger); }
.cc-toggle{
    position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%);
    display: flex; align-items: center; justify-content: center;
    width: 2.4rem; height: 2.4rem; border-radius: 0.7rem;
    background: none; border: none; cursor: pointer; font-size: 1rem; color: var(--cc-dim);
    transition: color 0.18s ease, background 0.18s ease;
}
.cc-toggle:hover{ color: var(--cc-gold); background: rgba(243,193,90,0.1); }
.cc-toggle:focus-visible{ outline: 3px solid rgba(243,193,90,0.55); outline-offset: 2px; }

.cc-row{ display: flex; align-items: center; justify-content: center; gap: 0.6rem 1.1rem; flex-wrap: wrap; }
.cc-check{ display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; user-select: none; font-size: 0.82rem; font-weight: 500; color: var(--cc-muted); }
.cc-check input{ position: absolute; opacity: 0; width: 0; height: 0; }
.cc-check-box{
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    width: 1.15rem; height: 1.15rem; border-radius: 0.35rem;
    border: 1.5px solid rgba(255,255,255,0.24); background: rgba(255,255,255,0.04); transition: all 0.16s ease;
}
.cc-check-box i{ font-size: 0.68rem; color: #16131c; opacity: 0; transform: scale(0.4); transition: all 0.16s ease; }
.cc-check:hover .cc-check-box{ border-color: var(--cc-gold); }
.cc-check input:checked + .cc-check-box{ background: var(--cc-gold); border-color: var(--cc-gold); }
.cc-check input:checked + .cc-check-box i{ opacity: 1; transform: scale(1); }
.cc-check input:focus-visible + .cc-check-box{ outline: 3px solid rgba(243,193,90,0.55); outline-offset: 2px; }
.cc-forgot{
    display: inline-flex; align-items: center; gap: 0.35rem;
    font-size: 0.82rem; font-weight: 600; color: var(--cc-gold); text-decoration: none; transition: color 0.18s ease;
}
.cc-forgot:hover{ color: var(--cc-gold-2); text-decoration: underline; text-underline-offset: 3px; }
.cc-forgot:focus-visible{ outline: 3px solid rgba(243,193,90,0.55); outline-offset: 2px; }

.cc-btn{
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center; gap: 0.5rem;
    width: 100%; height: 3.2rem; margin-top: 0.3rem; border: none; border-radius: 0.8rem; cursor: pointer;
    font-family: var(--cc-font-display); font-size: 0.96rem; font-weight: 700; letter-spacing: 0.01em; color: #16131c;
    background: linear-gradient(135deg, var(--cc-gold-2) 0%, var(--cc-gold) 55%, #e6a93c 100%);
    box-shadow: 0 16px 38px -14px rgba(243,193,90,0.7);
    transition: transform 0.18s ease, box-shadow 0.24s ease, filter 0.24s ease;
}
.cc-btn:hover{ transform: translateY(-2px); filter: brightness(1.05); box-shadow: 0 22px 48px -16px rgba(243,193,90,0.85); }
.cc-btn:active{ transform: translateY(0) scale(0.99); }
.cc-btn:disabled{ opacity: 0.75; cursor: wait; transform: none; }
.cc-btn:focus-visible{ outline: 3px solid rgba(243,193,90,0.55); outline-offset: 2px; }
.cc-btn .spinner{ display: none; animation: ccSpin 0.7s linear infinite; }
.cc-btn .btn-text{ white-space: nowrap; }
.cc-btn.loading .btn-text, .cc-btn.loading > .bi:not(.spinner){ display: none; }
.cc-btn.loading .spinner{ display: inline-block; }
@keyframes ccSpin{ from{ transform: rotate(0deg); } to{ transform: rotate(360deg); } }
.cc-btn::after{
    content: ""; position: absolute; inset: 0; pointer-events: none;
    transform: translateX(-130%) skewX(-18deg);
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
}
.cc-btn:hover::after{ animation: ccShine 0.9s ease; }
@keyframes ccShine{ to{ transform: translateX(130%) skewX(-18deg); } }

.cc-register{ margin-top: 1.3rem; text-align: center; position: relative; }
.cc-register p{ margin: 0 0 0.35rem; font-size: 0.82rem; color: var(--cc-muted); }
.cc-register a{ font-size: 0.86rem; font-weight: 700; color: var(--cc-gold-2); text-decoration: none; transition: color 0.18s ease; }
.cc-register a:hover{ color: var(--cc-gold); text-decoration: none; }
.cc-register a:focus-visible{ outline: 3px solid rgba(243,193,90,0.55); outline-offset: 2px; }

.cc-trust{ display: flex; align-items: center; justify-content: center; gap: 0.5rem 1rem; flex-wrap: wrap; margin-top: 1.15rem; padding-top: 1.1rem; border-top: 1px solid var(--cc-line); position: relative; }
.cc-trust-item{ display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.7rem; font-weight: 600; color: var(--cc-dim); }
.cc-trust-item i{ color: var(--cc-gold); }

/* ---- Footer ---- */
.cc-foot{ margin-top: 1.4rem; text-align: center; font-size: 0.72rem; color: var(--cc-dim); position: relative; }

@media (max-width: 480px){
    .cc-card{ padding: 2.1rem 1.4rem 1.6rem; border-radius: 1.25rem; }
    .cc-head h2{ font-size: 1.5rem; }
    .cc-back{ font-size: 0.72rem; padding: 0.45rem 0.8rem; }
}

@media (prefers-reduced-motion: reduce){
    .cc-card, .cc-mark::after{ animation: none; }
    .cc-btn:hover::after{ animation: none; }
    .cc-gridline{ animation: none; }
    .cc-dust i{ animation: none; opacity: 0; }
}
</style>

<div class="cc-stage">
    <div class="cc-gridline" aria-hidden="true"></div>

    <a href="{{ url('/') }}" class="cc-back"><i class="bi bi-arrow-left"></i> Back to store</a>

    <div class="cc-card">
        <div class="cc-dust" aria-hidden="true">
            <i class="d1"></i><i class="d2"></i><i class="d3"></i>
            <i class="d4"></i><i class="d5"></i><i class="d6"></i><i class="d7"></i>
        </div>

        <header class="cc-head">
            <span class="cc-mark"><i class="bi bi-currency-exchange"></i></span>
            <h2>Welcome <span>back</span></h2>
            <p>Sign in to manage your plans, payments &amp; purchases.</p>
        </header>

        @if($errors->any())
        <div class="cc-alert" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="cc-form" id="ccForm">
            @csrf

            <div class="cc-field">
                <label for="email" class="cc-label">Email address</label>
                <div class="cc-input-wrap">
                    <i class="bi bi-envelope-fill cc-input-icon" aria-hidden="true"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="cc-input @error('email') is-invalid @enderror"
                        placeholder="you@example.com" required autocomplete="email" autofocus inputmode="email">
                </div>
                @error('email')
                    <p class="cc-error"><i class="bi bi-info-circle-fill"></i> {{ $message }}</p>
                @enderror
            </div>

            <div class="cc-field">
                <label for="password" class="cc-label">Password</label>
                <div class="cc-input-wrap">
                    <i class="bi bi-lock-fill cc-input-icon" aria-hidden="true"></i>
                    <input id="password" type="password" name="password"
                        class="cc-input cc-input-has-toggle @error('password') is-invalid @enderror"
                        placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                        required autocomplete="current-password" spellcheck="false">
                    <button type="button" class="cc-toggle" id="ccToggle" aria-label="Show password">
                        <i class="bi bi-eye" id="ccIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="cc-error"><i class="bi bi-info-circle-fill"></i> {{ $message }}</p>
                @enderror
            </div>

            <div class="cc-row">
                <label class="cc-check" for="remember">
                    <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <span class="cc-check-box"><i class="bi bi-check-lg"></i></span>
                    Remember me
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="cc-forgot"><i class="bi bi-key-fill"></i> Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="cc-btn" id="ccBtn">
                <i class="bi bi-box-arrow-in-right"></i>
                <span class="btn-text">Sign in to {{ storeName() }}</span>
                <i class="bi bi-arrow-repeat spinner"></i>
            </button>
        </form>

        <div class="cc-register">
            <p>New to {{ storeName() }}?</p>
            <a href="{{ route('register') }}"><i class="bi bi-person-plus-fill"></i> Create free account</a>
        </div>

        <div class="cc-trust">
            <span class="cc-trust-item"><i class="bi bi-shield-fill-check"></i> Secured</span>
            <span class="cc-trust-item"><i class="bi bi-patch-check-fill"></i> Verified</span>
            <span class="cc-trust-item"><i class="bi bi-lock-fill"></i> Encrypted</span>
        </div>
    </div>

    <p class="cc-foot"><i class="bi bi-c-circle"></i> {{ date('Y') }} {{ storeName() }} &middot; Own at your own pace</p>
</div>

<script>
(function () {
    var toggle = document.getElementById('ccToggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            var input = document.getElementById('password');
            var icon = document.getElementById('ccIcon');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    }

    var form = document.getElementById('ccForm');
    if (form) {
        form.addEventListener('submit', function () {
            var btn = document.getElementById('ccBtn');
            if (btn) { btn.classList.add('loading'); btn.disabled = true; }
        });
    }
})();
</script>
@endsection