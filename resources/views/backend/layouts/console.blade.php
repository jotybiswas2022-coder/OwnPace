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
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="min-h-screen bg-paper-deep text-ink antialiased" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
    <div class="flex min-h-screen">
        <!-- ===== SIDEBAR ===== -->
        <div x-cloak x-show="sidebarOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 bg-ink/40 lg:hidden" @click="sidebarOpen = false"></div>

        <aside x-cloak x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-brand text-white lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 lg:transition-none">

            <div class="flex items-center gap-2.5 px-5 py-5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-mango text-ink">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity="0.45"/>
                        <circle cx="12" cy="12" r="4.5" fill="currentColor"/>
                    </svg>
                </span>
                <div class="leading-none">
                    <p class="font-display text-base font-bold">{{ storeName() }}</p>
                    <p class="mt-0.5 text-[10px] uppercase tracking-[0.16em] text-white/50">Admin Console</p>
                </div>
                <button type="button" class="ml-auto rounded-lg p-1.5 text-white/60 hover:bg-white/10 lg:hidden" @click="sidebarOpen = false" aria-label="Close menu">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>

            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 pb-6" aria-label="Admin navigation">
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
                        ['section' => 'Orders'],
                        ['route' => 'admin.orders.index', 'icon' => 'bi-receipt', 'label' => 'Orders', 'active' => request()->routeIs('admin.orders.*') && !request()->routeIs('admin.orders.fees*')],
                        ['route' => 'admin.orders.fees', 'icon' => 'bi-cash-coin', 'label' => 'Product Fees', 'active' => request()->routeIs('admin.orders.fees*')],
                        ['section' => 'Customers'],
                        ['route' => 'admin.users.index', 'icon' => 'bi-people-fill', 'label' => 'Customers', 'active' => request()->routeIs('admin.users.*')],
                        ['section' => 'Requests'],
                        ['route' => 'admin.requests.plan-changes', 'icon' => 'bi-arrow-repeat', 'label' => 'Plan Changes', 'active' => request()->routeIs('admin.requests.plan-changes*')],
                        ['route' => 'admin.requests.product-requests', 'icon' => 'bi-plus-circle', 'label' => 'Product Requests', 'active' => request()->routeIs('admin.requests.product-requests*')],
                        ['route' => 'admin.requests.exchange-requests', 'icon' => 'bi-arrow-left-right', 'label' => 'Exchanges', 'active' => request()->routeIs('admin.requests.exchange-requests*')],
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
                        ['route' => 'admin.analytics', 'icon' => 'bi-graph-up', 'label' => 'Analytics', 'active' => request()->routeIs('admin.analytics*')],
                    ];
                @endphp
                @foreach($nav as $item)
                    @if(isset($item['section']))
                        <p class="mt-5 px-3 pb-1 text-[10px] font-bold uppercase tracking-[0.14em] text-white/40">{{ $item['section'] }}</p>
                    @else
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ $item['active'] ? 'bg-mango text-ink shadow-soft' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                            <i class="bi {{ $item['icon'] }} w-5 text-center"></i> {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="border-t border-white/10 p-3">
                <a href="{{ url('/') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-white/60 transition-colors hover:bg-white/10 hover:text-white">
                    <i class="bi bi-house-fill"></i> Back to Store
                </a>
            </div>
        </aside>

        <!-- ===== MAIN ===== -->
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-ink/10 bg-paper/90 px-4 backdrop-blur-md sm:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" class="rounded-lg p-2 text-ink transition-colors hover:bg-brand/5 lg:hidden" @click="sidebarOpen = !sidebarOpen" aria-label="Toggle sidebar">
                        <svg viewBox="0 0 24 24" fill="none" class="h-6 w-6"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </button>
                    <h1 class="font-display text-base font-bold text-ink sm:text-lg">@yield('page_title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden items-center gap-2 text-sm text-slate sm:flex">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand font-display text-sm font-bold text-white">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="os-btn os-btn-ghost os-btn-sm" aria-label="Logout"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
