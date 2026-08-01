<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0A0A0B">
    <meta name="color-scheme" content="dark">
    <title>@yield('title', 'OwnPace Store — Buy Now, Pay in Installments')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        :root {
            color-scheme: dark;
            --gold-50: #FFFBEB;
            --gold-100: #FEF3C7;
            --gold-200: #FDE68A;
            --gold-400: #FACC15;
            --gold-500: #EAB308;
            --gold-600: #CA8A04;
            --gold-700: #A16207;
            --dark-50: #FAFAFA;
            --dark-100: #F4F4F5;
            --dark-200: #E4E4E7;
            --dark-400: #A1A1AA;
            --dark-600: #52525B;
            --dark-800: #27272A;
            --dark-900: #18181B;
            --dark-950: #09090B;
            --near-black: #0A0A0B;
            --surface-dark: #121214;
            --card-dark: #1A1A1E;
            --card-border: #2A2A2E;
            --text-primary: #F4F4F5;
            --text-muted: #A1A1AA;
            --text-dim: #71717A;
            --shadow-gold: 0 4px 20px rgba(234,179,8,0.15);
            --shadow-gold-lg: 0 8px 40px rgba(234,179,8,0.2);
            --shadow-card: 0 4px 24px rgba(0,0,0,0.3);
            --shadow-card-hover: 0 8px 40px rgba(234,179,8,0.1);
            --shadow-glow-sm: 0 0 15px rgba(234,179,8,0.05);
            --radius: 14px;
            --radius-sm: 8px;
            --radius-lg: 20px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--near-black);
            color: var(--text-primary);
            overflow-x: hidden;
            line-height: 1.6;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overscroll-behavior: none;
            font-variant-numeric: tabular-nums;
            -webkit-text-size-adjust: 100%;
            scrollbar-gutter: stable;
            print-color-adjust: exact;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background: radial-gradient(ellipse 80% 50% at 50% -20%, rgba(234,179,8,0.03) 0%, transparent 70%),
                        radial-gradient(ellipse 60% 40% at 80% 20%, rgba(234,179,8,0.02) 0%, transparent 60%),
                        radial-gradient(ellipse 40% 30% at 20% 80%, rgba(234,179,8,0.015) 0%, transparent 50%);
            pointer-events: none; z-index: 0;
        }

        a { text-decoration: none; color: inherit; }
        :focus-visible { outline: 2px solid var(--gold-500); outline-offset: 2px; border-radius: 4px; }
        a, button, input, select, textarea, [tabindex] { -webkit-tap-highlight-color: transparent; }
        input, textarea { caret-color: var(--gold-500); }
        input[type=checkbox], input[type=radio] { accent-color: var(--gold-500); }
        .fp-progress-bar, .fp-badge, .fp-tag, .fp-label, .fp-btn, .fp-card-badge, .fp-discount-badge { user-select: none; }
        select { appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23A1A1AA' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; }
        ::selection { background: var(--gold-500); color: var(--near-black); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--near-black); }
        ::-webkit-scrollbar-thumb { background: var(--card-border); border-radius: 99px; transition: background 0.3s; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold-600); }
        img { max-width: 100%; height: auto; }
        hr { border: none; height: 1px; background: var(--card-border); margin: 24px 0; }

        /* ===== PAGE LOADER ===== */
        #pageLoader {
            position: fixed; inset: 0; background: var(--near-black);
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; z-index: 99999;
            transition: opacity 0.6s ease, visibility 0.6s;
        }
        #pageLoader.hidden { opacity: 0; visibility: hidden; }
        .loader-logo {
            font-size: 36px; font-weight: 800; color: var(--gold-500);
            margin-bottom: 28px; font-family: 'Syne', sans-serif;
            animation: loaderPulse 1.4s ease-in-out infinite;
            letter-spacing: -1px;
        }
        .loader-logo span { color: var(--text-primary); }
        @keyframes loaderPulse { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.04);opacity:0.8} }
        .loader-logo-dot {
            display: inline-block; width: 10px; height: 10px;
            background: var(--gold-500); border-radius: 50%;
            margin-left: 6px; animation: loaderDot 1.4s ease-in-out infinite;
        }
        @keyframes loaderDot { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .loader-sub { color: var(--text-dim); font-size: 12px; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 20px; }
        .loader-bar { width: 200px; height: 3px; background: var(--card-dark); border-radius: 99px; overflow: hidden; }
        .loader-bar-fill { height: 100%; background: linear-gradient(90deg, var(--gold-500), var(--gold-400), var(--gold-500)); background-size: 200% 100%; border-radius: 99px; animation: loaderFill 1s linear infinite; }
        @keyframes loaderFill { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        /* ===== CURSOR GLOW ===== */
        #cursorGlow {
            position: fixed; pointer-events: none; z-index: 99998;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            transition: opacity 0.3s;
            will-change: transform, left, top;
        }

        /* ===== SCROLL TO TOP ===== */
        #scrollTop {
            position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            color: var(--near-black); border: none; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 20px;
            cursor: pointer; z-index: 999; opacity: 0; visibility: hidden;
            transform: translateY(20px); perspective: 1000px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: var(--shadow-gold);
        }
        #scrollTop.visible { opacity: 1; visibility: visible; transform: translateY(0); }
        #scrollTop:hover { background: var(--gold-600); transform: translateY(-3px) scale(1.05); box-shadow: var(--shadow-gold-lg); }

        /* ===== ALERTS ===== */
        .alert-success-custom, .alert-danger-custom {
            padding: 14px 20px; text-align: center; font-weight: 600; position: relative; z-index: 1000;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            animation: slideDownAlert 0.5s ease-out;
        }
        .alert-success-custom { background: linear-gradient(135deg, #166534, #15803D); color: #FEF9C3; }
        .alert-danger-custom { background: linear-gradient(135deg, #7F1D1D, #991B1B); color: #FECACA; }
        @keyframes slideDownAlert { from { transform: translateY(-100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* ===== BUTTONS ===== */
        .btn-primary-gold {
            display: inline-flex; align-items: center; gap: 8px; touch-action: manipulation;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            color: var(--near-black); padding: 12px 28px; border-radius: var(--radius-sm);
            font-weight: 700; font-size: 14px; border: none; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-family: inherit; position: relative; overflow: hidden;
        }
        .btn-primary-gold::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.15) 50%, transparent 80%);
            transform: translateX(-100%); transition: transform 0.6s;
        }
        .btn-primary-gold:hover::before { transform: translateX(100%); }
        .btn-primary-gold:hover { transform: translateY(-2px); box-shadow: var(--shadow-gold-lg); color: var(--near-black); }

        /* ===== SweetAlert Button Overrides ===== */
        .fp-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 8px 16px; border-radius: 6px;
            font-size: 12px; font-weight: 600; border: none; cursor: pointer;
            font-family: inherit; transition: all 0.2s;
        }
        .fp-btn-gold { background: var(--gold-500); color: var(--near-black); }
        .fp-btn-gold:hover { background: var(--gold-600); }
        .fp-btn-danger { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
        .fp-btn-danger:hover { background: rgba(239,68,68,0.2); }
        /* SweetAlert Popup Custom Styles */
        .fp-swal-popup {
            border: 1px solid #2A2A2E !important;
            border-radius: 16px !important;
            padding: 24px !important;
        }
        .fp-swal-popup .swal2-title {
            font-family: 'Syne', sans-serif !important;
            font-weight: 700 !important;
        }
        .fp-swal-popup .fp-btn { margin: 0 4px; }

        .btn-outline-gold {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent; color: var(--gold-400);
            padding: 12px 28px; border-radius: var(--radius-sm);
            font-weight: 600; font-size: 14px; border: 2px solid var(--gold-500);
            cursor: pointer; transition: all 0.3s; font-family: inherit;
            touch-action: manipulation;
        }
        .btn-outline-gold:hover { background: rgba(234,179,8,0.1); color: var(--gold-300); }

        /* ===== SECTION UTILITIES ===== */
        .section-padding { padding: 80px 0; }
        .section-head {
            text-align: center; margin-bottom: 48px;
        }
        .section-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(234,179,8,0.1); color: var(--gold-400);
            border: 1px solid rgba(234,179,8,0.2);
            padding: 6px 16px; border-radius: 99px;
            font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 14px;
        }
        .section-head h2 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(28px, 4vw, 40px);
            font-weight: 800; color: var(--text-primary);
            margin-bottom: 12px;
        }
        .section-head p { color: var(--text-muted); font-size: 16px; max-width: 600px; margin: 0 auto; }

        .card-dark {
            background: var(--card-dark);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .card-dark:hover {
            border-color: rgba(234,179,8,0.3);
            box-shadow: var(--shadow-card-hover);
            transform: translateY(-4px);
        }

        .counter-num {
            font-family: 'Syne', sans-serif;
            font-size: 36px; font-weight: 800;
            background: linear-gradient(135deg, var(--gold-400), var(--gold-600));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        /* ===== REVEAL ANIMATIONS ===== */
        .reveal-up {
            opacity: 0; transform: translateY(30px);
            transition: opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .reveal-up.visible { opacity: 1; transform: translateY(0); }

        .reveal-scale {
            opacity: 0; transform: scale(0.9);
            transition: opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .reveal-scale.visible { opacity: 1; transform: scale(1); }

        .reveal-left {
            opacity: 0; transform: translateX(-40px);
            transition: opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .reveal-left.visible { opacity: 1; transform: translateX(0); }

        .reveal-right {
            opacity: 0; transform: translateX(40px);
            transition: opacity 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .reveal-right.visible { opacity: 1; transform: translateX(0); }

        /* ===== SECTION DIVIDER ===== */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(234,179,8,0.12), transparent);
            margin: 0;
            border: none;
        }

        /* ===== GRAIN TEXTURE ===== */
        .grain-overlay {
            position: fixed; inset: 0; pointer-events: none; z-index: 99997;
            opacity: 0.012;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            background-repeat: repeat; background-size: 256px 256px;
        }

        /* ===== TEXT GRADIENT UTILITY ===== */
        .text-gradient-gold {
            background: linear-gradient(135deg, var(--gold-400), var(--gold-600));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== GLASS CARD UTILITY ===== */
        .glass-card {
            background: rgba(26,26,30,0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.06);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .section-padding { padding: 50px 0; }
            #cursorGlow { display: none; }
        }

        /* ===== MOBILE BOTTOM NAV ===== */
        .fp-bottom-nav {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: space-around;
            background: rgba(18,18,20,0.92);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 6px 0 env(safe-area-inset-bottom, 6px);
            box-shadow: 0 -4px 30px rgba(0,0,0,0.4);
            animation: bottomNavSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transform: translateY(100%);
        }
        @keyframes bottomNavSlideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }

        .fp-bn-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 4px 0;
            text-decoration: none;
            color: #71717a;
            transition: all 0.25s ease;
            position: relative;
            min-width: 52px;
            -webkit-tap-highlight-color: transparent;
        }
        .fp-bn-item:active {
            transform: scale(0.92);
        }

        .fp-bn-icon-wrap {
            position: relative;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .fp-bn-item .fp-bn-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.3s ease;
            line-height: 1;
        }

        /* Active state */
        .fp-bn-active {
            color: #eab308 !important;
        }
        .fp-bn-active .fp-bn-icon-wrap {
            transform: translateY(-2px);
        }
        .fp-bn-active .fp-bn-label {
            color: #eab308;
            font-weight: 700;
        }

        /* Active indicator dot */
        .fp-bn-active::after {
            content: '';
            position: absolute;
            top: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #eab308;
            border-radius: 0 0 3px 3px;
            box-shadow: 0 0 10px rgba(234,179,8,0.3);
        }

        /* Cart badge */
        .fp-bn-badge {
            position: absolute;
            top: -4px;
            right: -8px;
            background: #eab308;
            color: #0A0A0B;
            font-size: 8px;
            font-weight: 800;
            min-width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            box-shadow: 0 2px 8px rgba(234,179,8,0.3);
        }

        /* Avatar in bottom nav */
        .fp-bn-avatar {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: linear-gradient(135deg, #eab308, #ca8a04);
            color: #0A0A0B;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
        }
        .fp-bn-active .fp-bn-avatar {
            box-shadow: 0 0 0 2px rgba(234,179,8,0.3);
        }

        /* Hover effect */
        @media (hover: hover) {
            .fp-bn-item:hover {
                color: #a1a1aa;
            }
            .fp-bn-item:hover .fp-bn-icon-wrap {
                transform: translateY(-1px);
            }
        }

        /* Scroll hiding */
        .fp-bottom-nav.fp-bn-hidden {
            transform: translateY(100%);
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .fp-bottom-nav:not(.fp-bn-hidden) {
            transform: translateY(0);
            opacity: 1;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Body padding for bottom nav */
        @media (max-width: 991px) {
            body {
                padding-bottom: 60px;
            }
        }

        /* Safe area for notched devices */
        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            .fp-bottom-nav {
                padding-bottom: calc(6px + env(safe-area-inset-bottom));
            }
            @media (max-width: 991px) {
                body {
                    padding-bottom: calc(60px + env(safe-area-inset-bottom));
                }
            }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>
    <div class="grain-overlay" aria-hidden="true"></div>

    <div id="cursorGlow" aria-hidden="true"></div>

    <div id="pageLoader">
        <div class="loader-logo">Flexi<span>Pay</span><span class="loader-logo-dot"></span></div>
        <div class="loader-sub">Loading amazing deals</div>
        <div class="loader-bar" role="progressbar" aria-label="Loading progress"><div class="loader-bar-fill"></div></div>
    </div>

    <button id="scrollTop" aria-label="Scroll to top" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="bi bi-chevron-up"></i></button>

    @include('frontend.partials.menu')
    @yield('content')

    <!-- ===== MOBILE BOTTOM NAV ===== -->
    <nav class="fp-bottom-nav d-lg-none" id="fpBottomNav" aria-label="Mobile navigation">
        @php
            $cartItems = session('cart', []);
            $cartCount = is_array($cartItems) ? count($cartItems) : (is_object($cartItems) && method_exists($cartItems, 'count') ? $cartItems->count() : 0);
            $currentPath = request()->path();
        @endphp
        <a href="{{ url('/') }}" class="fp-bn-item {{ request()->is('/') ? 'fp-bn-active' : '' }}">
            <span class="fp-bn-icon-wrap"><i class="bi bi-house-fill"></i></span>
            <span class="fp-bn-label">Home</span>
        </a>
        <a href="{{ url('/shop') }}" class="fp-bn-item {{ request()->is('shop') ? 'fp-bn-active' : '' }}">
            <span class="fp-bn-icon-wrap"><i class="bi bi-grid-fill"></i></span>
            <span class="fp-bn-label">Shop</span>
        </a>
        <a href="{{ url('/cart') }}" class="fp-bn-item {{ request()->is('cart') ? 'fp-bn-active' : '' }}">
            <span class="fp-bn-icon-wrap">
                <i class="bi bi-cart-fill"></i>
                @if($cartCount > 0)
                    <span class="fp-bn-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                @endif
            </span>
            <span class="fp-bn-label">Cart</span>
        </a>
        <a href="{{ url('/wishlist') }}" class="fp-bn-item {{ request()->is('wishlist') ? 'fp-bn-active' : '' }}">
            <span class="fp-bn-icon-wrap"><i class="bi bi-heart-fill"></i></span>
            <span class="fp-bn-label">Wishlist</span>
        </a>
        @auth
            <a href="{{ url('/profile') }}" class="fp-bn-item {{ request()->is('profile*') ? 'fp-bn-active' : '' }}">
                <span class="fp-bn-icon-wrap">
                    <span class="fp-bn-avatar">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                </span>
                <span class="fp-bn-label">Profile</span>
            </a>
        @else
            <a href="{{ url('/login') }}" class="fp-bn-item {{ request()->is('login*') ? 'fp-bn-active' : '' }}">
                <span class="fp-bn-icon-wrap"><i class="bi bi-person-fill"></i></span>
                <span class="fp-bn-label">Login</span>
            </a>
        @endauth
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    <script>
        // Page Loader
        window.addEventListener('load', () => {
            setTimeout(() => { document.getElementById('pageLoader').classList.add('hidden'); }, 800);
        });

        // Scroll to Top
        window.addEventListener('scroll', () => {
            const s = document.getElementById('scrollTop');
            window.scrollY > 400 ? s.classList.add('visible') : s.classList.remove('visible');
        });

        // Custom Cursor Glow
        const cursorGlow = document.getElementById('cursorGlow');
        let mouseX = -300, mouseY = -300;
        let cursorX = -300, cursorY = -300;

        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        function animateCursor() {
            cursorX += (mouseX - cursorX) * 0.08;
            cursorY += (mouseY - cursorY) * 0.08;
            cursorGlow.style.left = cursorX + 'px';
            cursorGlow.style.top = cursorY + 'px';
            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        // Scroll Reveal
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        document.querySelectorAll('.reveal-up, .reveal-scale, .reveal-left, .reveal-right').forEach(el => revealObs.observe(el));

        // Counter Animation
        const counterObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const el = e.target;
                    const target = parseInt(el.dataset.count);
                    if (!target) { counterObs.unobserve(el); return; }
                    const dur = 2000, step = target / (dur / 16);
                    let cur = 0;
                    const t = setInterval(() => {
                        cur += step;
                        if (cur >= target) { cur = target; clearInterval(t); }
                        el.textContent = Math.floor(cur).toLocaleString();
                    }, 16);
                    counterObs.unobserve(el);
                }
            });
        }, { threshold: 0.5 });
        document.querySelectorAll('[data-count]').forEach(el => counterObs.observe(el));

        // Bottom Nav Scroll Hide
        (function() {
            const bottomNav = document.getElementById('fpBottomNav');
            if (!bottomNav) return;
            let lastScroll = 0;
            let scrollDirection = 'up';
            const threshold = 50;

            window.addEventListener('scroll', () => {
                const currentScroll = window.scrollY;
                const newDirection = currentScroll > lastScroll ? 'down' : 'up';
                
                if (currentScroll < threshold) {
                    bottomNav.classList.remove('fp-bn-hidden');
                } else if (newDirection === 'down' && scrollDirection === 'up') {
                    bottomNav.classList.add('fp-bn-hidden');
                } else if (newDirection === 'up' && scrollDirection === 'down') {
                    bottomNav.classList.remove('fp-bn-hidden');
                }
                
                scrollDirection = newDirection;
                lastScroll = currentScroll;
            }, { passive: true });

            // Show on touch end / release
            document.addEventListener('touchend', () => {
                if (window.scrollY > threshold) {
                    // Brief show on touch end, then hide after delay
                    bottomNav.classList.remove('fp-bn-hidden');
                    clearTimeout(window._bnTimeout);
                    window._bnTimeout = setTimeout(() => {
                        bottomNav.classList.add('fp-bn-hidden');
                    }, 2000);
                }
            }, { passive: true });

            // Always show at top of page
            window.addEventListener('scroll', () => {
                if (window.scrollY < threshold) {
                    bottomNav.classList.remove('fp-bn-hidden');
                }
            }, { passive: true });
        })();

        // Parallax on mouse move for elements with data-tilt
        document.querySelectorAll('[data-tilt]').forEach(el => {
            el.addEventListener('mousemove', (e) => {
                const rect = el.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5;
                const y = (e.clientY - rect.top) / rect.height - 0.5;
                const intensity = parseFloat(el.dataset.tilt) || 10;
                el.style.transform = `perspective(1000px) rotateY(${x * intensity}deg) rotateX(${-y * intensity}deg)`;
            });
            el.addEventListener('mouseleave', () => {
                el.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
                el.style.transition = 'transform 0.5s ease';
                setTimeout(() => { el.style.transition = ''; }, 500);
            });
        });

        // ===== SweetAlert2 Global Config =====
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: '#1A1A1E',
            color: '#F4F4F5',
            iconColor: '#EAB308',
        });

        // Auto-convert confirm() dialogs to SweetAlert
        document.addEventListener('click', function(e) {
            const el = e.target.closest('[onclick]');
            if (!el) return;
            const match = el.getAttribute('onclick')?.match(/return confirm\(['"](.+?)['"]\)/);
            if (!match) return;
            e.preventDefault();
            const msg = match[1];
            const href = el.getAttribute('href') || '';
            const form = el.closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EAB308',
                cancelButtonColor: '#ef4444',
                confirmButtonText: '<i class="bi bi-check-lg"></i> Yes, proceed',
                cancelButtonText: '<i class="bi bi-x-lg"></i> Cancel',
                background: '#1A1A1E',
                color: '#F4F4F5',
                iconColor: '#EAB308',
                reverseButtons: true,
                customClass: {
                    popup: 'fp-swal-popup',
                    confirmButton: 'fp-btn fp-btn-gold',
                    cancelButton: 'fp-btn fp-btn-danger',
                }
            }).then(result => {
                if (!result.isConfirmed) return;
                if (form) { form.submit(); }
                else if (href) { window.location.href = href; }
            });
        });

        // Show Laravel session flashes as SweetAlert toasts
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
            Toast.fire({ icon: 'success', title: @json(session('success')) });
            @endif
            @if(session('error'))
            Toast.fire({ icon: 'error', title: @json(session('error')) });
            @endif
            @if(session('warning'))
            Toast.fire({ icon: 'warning', title: @json(session('warning')) });
            @endif
            @if(session('info'))
            Toast.fire({ icon: 'info', title: @json(session('info')) });
            @endif
        });
    </script>
</body>
</html>