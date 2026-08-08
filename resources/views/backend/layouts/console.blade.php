<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2e2a6b">
    <title>@yield('title', storeName().' Admin')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.os-inline-styles')
    @livewireStyles
    @stack('styles')

    <style>
        /* ============================================================
           Admin Console — premium chrome
           ============================================================ */

        /* Tailwind v4 theme/utilities are imported without preflight, so
           border-box is never applied — force it here to keep padding
           inside element widths (prevents horizontal overflow). */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* ---- Sidebar: deep indigo gradient with gold + violet glows ---- */
        .ac-sidebar {
            background:
                radial-gradient(560px 300px at 88% -12%, rgba(245, 166, 35, 0.16), transparent 62%),
                radial-gradient(460px 340px at -12% 112%, rgba(124, 105, 255, 0.28), transparent 58%),
                linear-gradient(180deg, #211e52 0%, #2e2a6b 55%, #19163f 100%);
        }
        .ac-sidebar .ac-mark {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 0.8rem;
            font-size: 1.15rem;
            color: #16131c;
            background: linear-gradient(135deg, #ffd88f, #f5a623);
            box-shadow: 0 10px 26px -10px rgba(245, 166, 35, 0.85);
        }
        .ac-sidebar .ac-brand-name {
            font-family: var(--font-display);
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .ac-sidebar .ac-brand-sub {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.45);
        }
        .ac-sidebar .ac-nav-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0 0.75rem;
            margin-top: 1.4rem;
            margin-bottom: 0.4rem;
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.38);
        }
        .ac-sidebar .ac-nav-label::before {
            content: "";
            width: 0.4rem;
            height: 0.4rem;
            border-radius: 9999px;
            background: #f5a623;
            box-shadow: 0 0 10px rgba(245, 166, 35, 0.8);
        }
        .ac-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.62rem 0.75rem;
            margin-bottom: 0.15rem;
            border-radius: 0.7rem;
            font-size: 0.84rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.68);
            text-decoration: none;
            transition: color 0.18s ease, background 0.18s ease, transform 0.18s ease;
        }
        .ac-link i {
            width: 1.15rem;
            text-align: center;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.5);
            transition: color 0.18s ease;
        }
        .ac-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(2px);
        }
        .ac-link:hover i { color: #ffd88f; }
        .ac-link.is-active {
            color: #16131c;
            font-weight: 600;
            background: linear-gradient(135deg, #ffd88f 0%, #f5a623 60%, #eda33c 100%);
            box-shadow: 0 10px 26px -12px rgba(245, 166, 35, 0.9);
        }
        .ac-link.is-active i { color: #16131c; }
        .ac-link:focus-visible {
            outline: 3px solid rgba(245, 166, 35, 0.6);
            outline-offset: 2px;
        }
        .ac-sidebar nav::-webkit-scrollbar { width: 6px; }
        .ac-sidebar nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.14);
            border-radius: 9999px;
        }
        .ac-sidebar nav::-webkit-scrollbar-track { background: transparent; }

        /* ---- Header: frosted glass ---- */
        .ac-header {
            background: rgba(255, 255, 255, 0.82);
            -webkit-backdrop-filter: blur(18px) saturate(160%);
            backdrop-filter: blur(18px) saturate(160%);
            border-bottom: 1px solid rgba(26, 27, 35, 0.08);
            box-shadow: 0 10px 30px -22px rgba(46, 42, 107, 0.35);
        }
        .ac-hamburger {
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.7rem;
            border: 1px solid rgba(26, 27, 35, 0.1);
            background: #fff;
            color: #1a1b23;
            transition: border-color 0.18s ease, background 0.18s ease, transform 0.15s ease;
        }
        .ac-hamburger:hover {
            border-color: rgba(245, 166, 35, 0.6);
            background: rgba(245, 166, 35, 0.08);
            transform: translateY(-1px);
        }
        .ac-user-chip {
            align-items: center;
            gap: 0.55rem;
            padding: 0.3rem 0.65rem 0.3rem 0.3rem;
            border-radius: 9999px;
            background: #fff;
            border: 1px solid rgba(26, 27, 35, 0.1);
            box-shadow: 0 8px 20px -14px rgba(46, 42, 107, 0.4);
            transition: border-color 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
        }
        .ac-user-chip:hover {
            border-color: rgba(245, 166, 35, 0.55);
            box-shadow: 0 12px 26px -16px rgba(46, 42, 107, 0.55);
            transform: translateY(-1px);
        }
        .ac-user-chip .ac-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #4a4599, #2e2a6b);
            box-shadow: inset 0 0 0 1.5px rgba(255, 255, 255, 0.25);
        }
        .ac-logout-btn {
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.7rem;
            border: 1px solid rgba(26, 27, 35, 0.1);
            background: #fff;
            color: #5d6771;
            cursor: pointer;
            transition: color 0.18s ease, border-color 0.18s ease, background 0.18s ease, transform 0.15s ease;
        }
        .ac-logout-btn:hover {
            color: #b83228;
            border-color: rgba(224, 72, 62, 0.4);
            background: rgba(224, 72, 62, 0.07);
            transform: translateY(-1px);
        }

        /* ---- Main stage ---- */
        .ac-stage {
            width: 100%;
            max-width: 88rem;
            margin: 0 auto;
            padding: 1.25rem 1rem 3rem;
        }
        @media (min-width: 640px) {
            .ac-stage { padding: 1.75rem 1.75rem 3.5rem; }
        }
        @media (min-width: 1280px) {
            .ac-stage { padding: 2rem 2.25rem 4rem; }
        }

        /* Mobile drawer polish */
        .ac-drawer-backdrop { background: rgba(17, 14, 44, 0.55); }

        @media (prefers-reduced-motion: reduce) {
            .ac-link, .ac-hamburger, .ac-user-chip, .ac-logout-btn { transition: none; }
        }
    </style>
</head>
<body class="min-h-screen bg-paper-deep text-ink antialiased" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    <div class="flex min-h-screen">
        <!-- ===== MOBILE BACKDROP ===== -->
        <div x-cloak x-show="sidebarOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="ac-drawer-backdrop fixed inset-0 z-30 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- ===== SIDEBAR ===== -->
        <aside x-cloak x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="ac-sidebar fixed inset-y-0 left-0 z-40 flex w-64 flex-col text-white lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 lg:transition-none">

            <div class="flex items-center gap-3 px-5 pt-5 pb-4">
                <span class="ac-mark">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity="0.45"/>
                        <circle cx="12" cy="12" r="4.5" fill="currentColor"/>
                    </svg>
                </span>
                <div class="leading-none">
                    <p class="ac-brand-name text-[15px] text-white">{{ storeName() }}</p>
                    <p class="ac-brand-sub mt-1">Admin Console</p>
                </div>
                <button type="button" class="ml-auto rounded-lg p-1.5 text-white/60 transition-colors hover:bg-white/10 lg:hidden" @click="sidebarOpen = false" aria-label="Close menu">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 pb-6" aria-label="Admin navigation">
                @php
                    $nav = [
                        ['route' => 'admin.dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'active' => request()->routeIs('admin.dashboard')],
                        ['section' => 'Shop'],
                        ['route' => 'admin.products.index', 'icon' => 'bi-box-seam-fill', 'label' => 'Products', 'active' => request()->routeIs('admin.products.*') && !request()->routeIs('admin.products.import*')],
                        ['route' => 'admin.products.import', 'icon' => 'bi-upload', 'label' => 'Import Products', 'active' => request()->routeIs('admin.products.import*')],
                        ['route' => 'admin.category.index', 'icon' => 'bi-tag-fill', 'label' => 'Categories', 'active' => request()->routeIs('admin.category.*')],
                        ['route' => 'admin.brands.index', 'icon' => 'bi-building', 'label' => 'Brands', 'active' => request()->routeIs('admin.brands.*')],
                        ['route' => 'admin.suppliers.index', 'icon' => 'bi-truck', 'label' => 'Suppliers', 'active' => request()->routeIs('admin.suppliers.*')],
                        ['route' => 'admin.promo-codes.index', 'icon' => 'bi-percent', 'label' => 'Promo Codes', 'active' => request()->routeIs('admin.promo-codes.*')],
                        ['section' => 'Financing'],
                        ['route' => 'admin.plans.index', 'icon' => 'bi-calendar2-week', 'label' => 'Installment Plans', 'active' => request()->routeIs('admin.plans.*')],
                        ['route' => 'admin.transactions.index', 'icon' => 'bi-credit-card-2-front', 'label' => 'Transactions', 'active' => request()->routeIs('admin.transactions.*')],
                        ['route' => 'admin.wallet.index', 'icon' => 'bi-wallet2', 'label' => 'Wallet Management', 'active' => request()->routeIs('admin.wallet.index*') || request()->routeIs('admin.wallet.credit*')],
                        ['route' => 'admin.wallet.withdrawals', 'icon' => 'bi-bank', 'label' => 'Withdrawals', 'active' => request()->routeIs('admin.wallet.withdrawals*')],
                        ['section' => 'Orders'],
                        ['route' => 'admin.orders.index', 'icon' => 'bi-receipt', 'label' => 'Orders', 'active' => request()->routeIs('admin.orders.*') && !request()->routeIs('admin.orders.fees*')],
                        ['route' => 'admin.orders.fees', 'icon' => 'bi-cash-coin', 'label' => 'Product Fees', 'active' => request()->routeIs('admin.orders.fees*')],
                        ['section' => 'Customers'],
                        ['route' => 'admin.users.index', 'icon' => 'bi-people-fill', 'label' => 'Customers', 'active' => request()->routeIs('admin.users.*')],
                        ['section' => 'Requests'],
                        ['route' => 'admin.requests.index', 'icon' => 'bi-inbox-fill', 'label' => 'Requests', 'active' => request()->routeIs('admin.requests.*')],
                        ['section' => 'Marketing'],
                        ['route' => 'admin.campaigns.index', 'icon' => 'bi-megaphone-fill', 'label' => 'Campaigns', 'active' => request()->routeIs('admin.campaigns.*')],
                        ['route' => 'admin.value-champions', 'icon' => 'bi-trophy-fill', 'label' => 'Value Champions', 'active' => request()->routeIs('admin.value-champions*')],
                        ['section' => 'Content'],
                        ['route' => 'admin.sliders.index', 'icon' => 'bi-images', 'label' => 'Sliders', 'active' => request()->routeIs('admin.sliders.*')],
                        ['route' => 'admin.faqs.index', 'icon' => 'bi-question-circle', 'label' => 'FAQs', 'active' => request()->routeIs('admin.faqs.*')],
                        ['route' => 'admin.terms.index', 'icon' => 'bi-file-earmark-text', 'label' => 'Terms', 'active' => request()->routeIs('admin.terms.*')],
                        ['route' => 'admin.contacts.index', 'icon' => 'bi-envelope', 'label' => 'Contacts', 'active' => request()->routeIs('admin.contacts.*')],
                        ['section' => 'Settings'],
                        ['route' => 'admin.settings', 'icon' => 'bi-gear-fill', 'label' => 'Settings', 'active' => request()->routeIs('admin.settings*')],
                        ['route' => 'admin.secure-config.index', 'icon' => 'bi-shield-lock-fill', 'label' => 'Secure Config', 'active' => request()->routeIs('admin.secure-config*'), 'super_admin_only' => true],
                        ['route' => 'admin.roles.index', 'icon' => 'bi-person-badge-fill', 'label' => 'Roles & Permissions', 'active' => request()->routeIs('admin.roles.*'), 'super_admin_only' => true],
                        ['route' => 'admin.reporting.index', 'icon' => 'bi-graph-up-arrow', 'label' => 'Reporting', 'active' => request()->routeIs('admin.reporting*')],
                    ];
                @endphp
                @foreach($nav as $item)
                    @if(isset($item['section']))
                        <p class="ac-nav-label">{{ $item['section'] }}</p>
                    @elseif(!empty($item['super_admin_only']) && !auth()->user()->isSuperAdmin())
                        {{-- Super Admin only links are hidden for everyone else --}}
                    @else
                        <a href="{{ route($item['route']) }}" class="ac-link {{ $item['active'] ? 'is-active' : '' }}">
                            <i class="bi {{ $item['icon'] }}"></i> {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="border-t border-white/10 p-3">
                <a href="{{ url('/') }}" class="ac-link !mb-0">
                    <i class="bi bi-house-fill"></i> Back to Store
                </a>
            </div>
        </aside>

        <!-- ===== MAIN ===== -->
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="ac-header sticky top-0 z-20 flex h-16 items-center justify-between gap-4 px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" class="ac-hamburger inline-flex lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                    <div>
                        <p class="hidden text-[10px] font-bold uppercase tracking-[0.16em] text-slate sm:block">Admin Console</p>
                        <h1 class="font-display text-base font-bold leading-tight text-ink sm:text-lg">@yield('page_title', 'Dashboard')</h1>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('admin.settings') }}" class="ac-logout-btn inline-flex hidden sm:inline-flex" title="Settings" aria-label="Settings">
                        <i class="bi bi-gear-fill"></i>
                    </a>
                    <span class="ac-user-chip inline-flex hidden sm:inline-flex">
                        <span class="ac-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                        <span class="text-sm font-semibold text-ink">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="ac-logout-btn" aria-label="Logout"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                </div>
            </header>

            <main class="flex-1">
                <div class="ac-stage">
                    @yield('breadcrumbs')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @include('frontend.partials.toasts')

    @livewireScripts
    @stack('scripts')
</body>
</html>
