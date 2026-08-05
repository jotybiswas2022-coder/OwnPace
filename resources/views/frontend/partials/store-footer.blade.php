@php
use App\Models\Setting;
$settings = Setting::first();
$email = $settings?->email ?? 'support@'.strtolower(str_replace(' ', '', storeName())).'.com';
$phone = $settings?->phone ?? '+234 800 000 0000';
$location = $settings?->location ?? 'Lagos, Nigeria';
@endphp

<footer class="footer-glass mt-20 relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="aurora-blob aurora-b" style="opacity:0.35;"></div>
    </div>
    <div class="grain" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6">
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="{{ url('/') }}" class="group flex items-center gap-2.5" aria-label="{{ storeName() }} home">
                    <span class="glass-dark flex h-10 w-10 items-center justify-center rounded-xl text-mango transition-transform duration-300 group-hover:rotate-6 group-hover:scale-110">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity="0.45"/>
                            <circle cx="12" cy="12" r="4.5" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="font-display text-lg font-bold tracking-tight text-white">{{ storeName() }}</span>
                </a>
                <p class="mt-5 max-w-xs text-sm leading-relaxed text-white/55">
                    Nigeria's installment store. Own what you love today, and pay it down at your own pace — no surprises, no hidden fees.
                </p>
                <div class="mt-6 space-y-2.5 text-sm text-white/60">
                    <p class="flex items-center gap-2.5"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-mango"><i class="bi bi-geo-alt-fill text-xs"></i></span> {{ $location }}</p>
                    <p class="flex items-center gap-2.5"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-mango"><i class="bi bi-telephone-fill text-xs"></i></span> {{ $phone }}</p>
                    <p class="flex items-center gap-2.5"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white/5 text-mango"><i class="bi bi-envelope-fill text-xs"></i></span> {{ $email }}</p>
                </div>
            </div>

            <!-- Shop -->
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-white">Shop</h3>
                <ul class="mt-5 space-y-3 text-sm text-white/60">
                    <li><a href="{{ url('/shop') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> All Products</a></li>
                    <li><a href="{{ url('/wishlist') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Wishlist</a></li>
                    <li><a href="{{ url('/cart') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Cart</a></li>
                    <li><a href="{{ url('/about') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> About Us</a></li>
                </ul>
            </div>

            <!-- Help -->
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-white">Help</h3>
                <ul class="mt-5 space-y-3 text-sm text-white/60">
                    <li><a href="{{ url('/faq') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> FAQs</a></li>
                    <li><a href="{{ url('/contact') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Contact Us</a></li>
                    <li><a href="{{ url('/legal') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Legal &amp; Policies</a></li>
                    <li><a href="{{ url('/terms') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Terms &amp; Conditions</a></li>
                    <li><a href="{{ url('/terms/payment') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Payment Plans</a></li>
                    <li><a href="{{ url('/terms/delivery') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Delivery Policy</a></li>
                    <li><a href="{{ url('/terms/privacy') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Privacy Policy</a></li>
                </ul>
            </div>

            <!-- How it works -->
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-white">How It Works</h3>
                <div class="mt-5 space-y-4">
                    @php
                        $steps = [
                            ['01', 'Pick your product', 'browse and choose what you want'],
                            ['02', 'Choose a plan', 'weekly, bi-weekly or monthly'],
                            ['03', 'Pay it down', 'watch the ring fill as you pay'],
                        ];
                    @endphp
                    @foreach($steps as [$num, $title, $sub])
                    <div class="group flex items-start gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-mango/25 bg-mango/10 font-mono text-xs font-bold text-mango transition-all duration-300 group-hover:scale-110 group-hover:bg-mango group-hover:text-ink">{{ $num }}</span>
                        <div>
                            <p class="text-sm font-semibold text-white">{{ $title }}</p>
                            @if(isset($sub))
                                <p class="text-xs text-white/50">{{ $sub }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-14 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-7 sm:flex-row">
            <p class="text-xs text-white/45">&copy; {{ date('Y') }} {{ storeName() }} — All rights reserved.</p>
            <div class="flex items-center gap-3">
                <span class="glass-dark flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold text-white/85"><span class="h-1.5 w-1.5 rounded-full bg-grass shadow-[0_0_8px_2px_rgba(47,158,68,0.5)]"></span> Secured payments</span>
                <span class="glass-dark flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold text-white/85"><span class="h-1.5 w-1.5 rounded-full bg-mango shadow-[0_0_8px_2px_rgba(245,166,35,0.5)]"></span> 256-bit SSL</span>
            </div>
        </div>
    </div>
</footer>
