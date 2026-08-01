<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0A0A0B">
    <meta name="color-scheme" content="dark">
    <title>OwnPace — @yield('title', 'Account')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&family=Syne:wght@700;800&family=Space+Grotesk:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold-400: #FACC15;
            --gold-500: #EAB308;
            --gold-600: #CA8A04;
            --gold-700: #A16207;
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
            --shadow-gold-lg: 0 8px 32px rgba(234,179,8,0.25);
            --radius-sm: 8px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0A0A0B 0%, #121214 50%, #0A0A0B 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            overscroll-behavior: none;
            -webkit-text-size-adjust: 100%;
            scrollbar-gutter: stable;
            print-color-adjust: exact;
        }
        a { text-decoration: none; }
        ::selection { background: var(--gold-500); color: var(--near-black); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--near-black); }
        ::-webkit-scrollbar-thumb { background: #27272A; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #52525B; }
        img { max-width: 100%; height: auto; }
        hr { border: none; height: 1px; background: var(--card-border); margin: 24px 0; }

        .fp-auth-nav {
            width: 100%;
            background: rgba(18,18,20,0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 14px 24px;
            border-bottom: 2px solid var(--gold-500);
            display: flex;
            align-items: center;
            justify-content: space-between;
            content-visibility: auto;
        }
        .fp-auth-brand { display: flex; align-items: center; gap: 10px; }
        .fp-auth-brand-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
            display: flex; align-items: center; justify-content: center;
            color: var(--near-black); font-size: 18px;
        }
        .fp-auth-brand span { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 20px; font-weight: 800; color: var(--text-primary); }
        .fp-auth-brand span span { color: var(--gold-500); }
        .fp-auth-home {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }
        .fp-auth-home:hover { color: var(--gold-400); }
        :focus-visible { outline: 2px solid var(--gold-500); outline-offset: 2px; border-radius: 4px; }
        a, button, input, select, textarea, [tabindex] { -webkit-tap-highlight-color: transparent; }
        input, textarea { caret-color: var(--gold-500); }
        input[type=checkbox], input[type=radio] { accent-color: var(--gold-500); }
        .fp-badge, .fp-tag, .fp-label, .fp-btn, .fp-card-badge { user-select: none; }
        select { appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23A1A1AA' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 12px center; background-size: 12px; }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
<body>
    <nav class="fp-auth-nav" aria-label="Site navigation">
        <a href="{{ url('/') }}" class="fp-auth-brand">
            <div class="fp-auth-brand-icon"><i class="bi bi-currency-exchange"></i></div>
            <span>Flexi<span>Pay</span></span>
        </a>
        <a href="{{ url('/') }}" class="fp-auth-home">
            <i class="bi bi-house-fill"></i> Back to Home
        </a>
    </nav>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
