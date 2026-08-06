<header class="nav-glass sticky top-0 z-40">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6">
        <!-- Brand -->
        <a href="{{ url('/') }}" class="group flex items-center gap-2.5" aria-label="{{ storeName() }} home">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand text-white shadow-soft transition-transform duration-300 group-hover:rotate-6 group-hover:scale-110">
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity="0.45"/>
                    <circle cx="12" cy="12" r="4.5" fill="currentColor"/>
                </svg>
            </span>
            <span class="flex flex-col leading-none">
                <span class="font-display text-lg font-bold tracking-tight text-ink">{{ storeName() }}</span>
                <span class="text-[10px] font-medium uppercase tracking-[0.14em] text-slate">Own at your own pace</span>
            </span>
        </a>

        <!-- Desktop nav -->
        <nav class="hidden items-center gap-1 lg:flex" aria-label="Main navigation">
            <a href="{{ url('/') }}" class="nav-link os-focus-ring rounded-md px-3 py-2 text-sm font-semibold text-slate transition-colors hover:text-brand {{ request()->is('/') ? 'active text-brand' : '' }}">Home</a>
            <a href="{{ url('/shop') }}" class="nav-link os-focus-ring rounded-md px-3 py-2 text-sm font-semibold text-slate transition-colors hover:text-brand {{ request()->is('shop') ? 'active text-brand' : '' }}">Shop</a>
            <a href="{{ url('/about') }}" class="nav-link os-focus-ring rounded-md px-3 py-2 text-sm font-semibold text-slate transition-colors hover:text-brand {{ request()->is('about') ? 'active text-brand' : '' }}">About</a>
            <a href="{{ url('/faq') }}" class="nav-link os-focus-ring rounded-md px-3 py-2 text-sm font-semibold text-slate transition-colors hover:text-brand {{ request()->is('faq') ? 'active text-brand' : '' }}">FAQs</a>
            <a href="{{ url('/contact') }}" class="nav-link os-focus-ring rounded-md px-3 py-2 text-sm font-semibold text-slate transition-colors hover:text-brand {{ request()->is('contact') ? 'active text-brand' : '' }}">Contact</a>
        </nav>

        <div class="flex items-center gap-2">
            <!-- Cart -->
            <a href="{{ url('/cart') }}" class="glass os-focus-ring relative rounded-xl p-2 text-ink transition-all duration-300 hover:-translate-y-0.5 hover:border-mango/40 hover:shadow-soft" aria-label="Cart">
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                    <path d="M6 7h12l-1.2 9.2a2 2 0 0 1-2 1.8H9.2a2 2 0 0 1-2-1.8L6 7Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M9 10V6a3 3 0 0 1 6 0v4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                @php $cartCount = cartCount(); @endphp
                <span class="js-cart-badge absolute -right-0.5 -top-0.5 flex h-4.5 min-w-[18px] items-center justify-center rounded-full bg-mango px-1 font-mono text-[10px] font-bold text-ink transition-all {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
            </a>

            @auth
            <a href="{{ url('/profile') }}" class="hidden sm:block">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand font-display text-sm font-bold text-white">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
            </a>
            @if(auth()->user()->is_admin == 1)
            <a href="{{ url('/admin') }}" class="os-btn os-btn-ghost os-btn-sm hidden md:inline-flex"><i class="bi bi-speedometer2"></i> Admin</a>
            @endif
            @else
            <a href="{{ url('/login') }}" class="os-focus-ring hidden rounded-md px-3 py-2 text-sm font-semibold text-ink transition-colors hover:text-brand sm:block">Login</a>
            <a href="{{ url('/register') }}" class="os-btn os-btn-mango os-btn-sm hidden sm:inline-flex">Get Started</a>
            @endauth

            <!-- Mobile menu button -->
            <button type="button" class="os-focus-ring rounded-lg p-2 text-ink transition-colors hover:bg-brand/5 lg:hidden" @click="mobileNavOpen = !mobileNavOpen" :aria-expanded="mobileNavOpen.toString()" aria-label="Toggle menu">
                <svg x-show="!mobileNavOpen" viewBox="0 0 24 24" fill="none" class="h-6 w-6" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <svg x-cloak x-show="mobileNavOpen" viewBox="0 0 24 24" fill="none" class="h-6 w-6" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-cloak x-show="mobileNavOpen" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="border-t border-ink/5 bg-white/85 backdrop-blur-xl lg:hidden">
        <nav class="mx-auto max-w-7xl space-y-1 px-4 py-3" aria-label="Mobile navigation">
            <a href="{{ url('/') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->is('/') ? 'bg-brand/5 text-brand' : 'text-slate' }}">Home</a>
            <a href="{{ url('/shop') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->is('shop') ? 'bg-brand/5 text-brand' : 'text-slate' }}">Shop</a>
            @auth
            <a href="{{ url('/orders') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->is('orders*') ? 'bg-brand/5 text-brand' : 'text-slate' }}">My Orders</a>
            <a href="{{ url('/wallet') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->is('wallet*') ? 'bg-brand/5 text-brand' : 'text-slate' }}">Wallet</a>
            <a href="{{ url('/wishlist') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->is('wishlist*') ? 'bg-brand/5 text-brand' : 'text-slate' }}">Wishlist</a>
            <a href="{{ url('/profile') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold {{ request()->is('profile*') ? 'bg-brand/5 text-brand' : 'text-slate' }}">Profile</a>
            @else
            <a href="{{ url('/login') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate">Login</a>
            <a href="{{ url('/register') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-slate">Create Account</a>
            @endauth
        </nav>
    </div>
</header>
