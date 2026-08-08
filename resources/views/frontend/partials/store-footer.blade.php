@php
use App\Models\Setting;
$settings = Setting::first();
$email = $settings?->email ?? 'support@'.strtolower(str_replace(' ', '', storeName())).'.com';
$phone = $settings?->phone ?? '+234 800 000 0000';
$location = $settings?->location ?? 'Lagos, Nigeria';
$description = $settings?->store_description ?: "Nigeria's installment store. Own what you love today, and pay it down at your own pace — no surprises, no hidden fees.";
@endphp

<footer class="footer-glass relative mt-20 overflow-hidden">
    <!-- Gradient accent line (matches nav) -->
    <div class="h-0.5 bg-gradient-to-r from-mango via-brand to-mango" aria-hidden="true"></div>

    <!-- Aurora + grain -->
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="aurora-blob aurora-b" style="opacity:0.4;"></div>
        <div class="aurora-blob aurora-a" style="width:26rem;height:26rem;top:-10rem;right:-8rem;opacity:0.22;"></div>
    </div>
    <div class="grain" aria-hidden="true"></div>

    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6">
        <!-- Mobile compact footer -->
        <div class="flex flex-col items-center gap-6 md:hidden">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5" aria-label="{{ storeName() }} home">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand to-brand-soft text-mango">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity="0.45"/>
                        <circle cx="12" cy="12" r="4.5" fill="currentColor"/>
                    </svg>
                    <span class="absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full bg-mango ring-2 ring-white/20"></span>
                </span>
                <span class="font-display text-lg font-bold tracking-tight text-white">{{ storeName() }}</span>
            </a>
            <nav class="flex items-center gap-4 text-xs font-semibold text-white/70" aria-label="Footer">
                <a href="{{ url('/shop') }}" class="transition-colors hover:text-mango">Shop</a>
                <a href="{{ url('/faq') }}" class="transition-colors hover:text-mango">FAQs</a>
                <a href="{{ url('/contact') }}" class="transition-colors hover:text-mango">Contact</a>
                <a href="{{ url('/legal') }}" class="transition-colors hover:text-mango">Policies</a>
            </nav>
        </div>

        <!-- Trust strip -->
        <div class="hidden flex-wrap items-center justify-center gap-x-3 gap-y-2.5 border-b border-white/10 pb-8 md:flex">
            <span class="glass-chip gap-1.5"><i class="bi bi-shield-check"></i> 256-bit SSL payments</span>
            <span class="glass-chip gap-1.5"><i class="bi bi-coin"></i> 0% interest plans</span>
            <span class="glass-chip gap-1.5"><i class="bi bi-arrow-repeat"></i> 30-day easy exchange</span>
            <span class="glass-chip gap-1.5"><i class="bi bi-truck"></i> Nationwide delivery</span>
        </div>

        <div class="mt-12 hidden gap-12 md:grid md:grid-cols-2 lg:grid-cols-4">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="{{ url('/') }}" class="group inline-flex items-center gap-2.5" aria-label="{{ storeName() }} home">
                    <span class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-deep via-brand-soft to-brand text-mango shadow-lift ring-1 ring-mango/40 transition-transform duration-300 group-hover:rotate-6 group-hover:scale-110">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity="0.45"/>
                            <circle cx="12" cy="12" r="4.5" fill="currentColor"/>
                        </svg>
                        <span class="absolute -right-1 -top-1 h-2.5 w-2.5 rounded-full bg-mango shadow-[0_0_10px_2px_rgba(245,166,35,0.55)] ring-2 ring-white/20"></span>
                    </span>
                    <span class="font-display text-lg font-bold tracking-tight text-white">{{ storeName() }}</span>
                </a>
                <p class="mt-5 max-w-xs text-sm leading-relaxed text-white/55">{{ $description }}</p>

                <div class="mt-6 space-y-2.5 text-sm text-white/60">
                    <p class="flex items-center gap-2.5"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-mango-soft to-mango-deep text-ink shadow-soft"><i class="bi bi-geo-alt-fill text-xs"></i></span> {{ $location }}</p>
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="group flex items-center gap-2.5 transition-colors hover:text-mango"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-mango-soft to-mango-deep text-ink shadow-soft transition-transform duration-300 group-hover:scale-110"><i class="bi bi-telephone-fill text-xs"></i></span> {{ $phone }}</a>
                    <a href="mailto:{{ $email }}" class="group flex items-center gap-2.5 transition-colors hover:text-mango"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-mango-soft to-mango-deep text-ink shadow-soft transition-transform duration-300 group-hover:scale-110"><i class="bi bi-envelope-fill text-xs"></i></span> {{ $email }}</a>
                </div>

                <!-- CTA panel -->
                <div class="glass-dark os-shine relative mt-7 overflow-hidden rounded-2xl p-5">
                    <p class="font-display text-sm font-bold text-white">Own it today.</p>
                    <p class="mt-1 text-xs leading-relaxed text-white/55">Pay it down at your own pace — weekly or monthly plans from a tiny deposit.</p>
                    <a href="{{ url('/shop') }}" class="ft-cta mt-4 inline-flex items-center gap-2 rounded-xl bg-mango px-4 py-2 text-xs font-bold shadow-soft transition-all duration-300 hover:-translate-y-0.5 hover:bg-mango-deep hover:shadow-lift">
                        <i class="bi bi-grid-fill"></i> Shop now
                    </a>
                </div>
            </div>

            <!-- Shop -->
            <div>
                <h3 class="flex items-center gap-2.5 font-display text-[13px] font-bold uppercase tracking-[0.18em] text-white">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-gradient-to-br from-mango-soft to-mango-deep text-ink shadow-soft"><i class="bi bi-grid-fill text-[10px]"></i></span> Shop
                </h3>
                <div class="mt-3 h-px w-12 bg-gradient-to-r from-mango/60 to-transparent" aria-hidden="true"></div>
                <ul class="mt-5 space-y-3 text-sm text-white/60">
                    <li><a href="{{ url('/shop') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> All Products</a></li>
                    <li><a href="{{ url('/wishlist') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Wishlist</a></li>
                    <li><a href="{{ url('/cart') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Cart</a></li>
                    <li><a href="{{ url('/about') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> About Us</a></li>
                    <li><a href="{{ url('/contact') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Contact Us</a></li>
                </ul>
            </div>

            <!-- Help -->
            <div>
                <h3 class="flex items-center gap-2.5 font-display text-[13px] font-bold uppercase tracking-[0.18em] text-white">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-gradient-to-br from-mango-soft to-mango-deep text-ink shadow-soft"><i class="bi bi-life-preserver text-[10px]"></i></span> Help
                </h3>
                <div class="mt-3 h-px w-12 bg-gradient-to-r from-mango/60 to-transparent" aria-hidden="true"></div>
                <ul class="mt-5 space-y-3 text-sm text-white/60">
                    <li><a href="{{ url('/faq') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> FAQs</a></li>
                    <li><a href="{{ url('/legal') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Legal &amp; Policies</a></li>
                    <li><a href="{{ url('/terms') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Terms &amp; Conditions</a></li>
                    <li><a href="{{ url('/terms/payment') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Payment Plans</a></li>
                    <li><a href="{{ url('/terms/delivery') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Delivery Policy</a></li>
                    <li><a href="{{ url('/terms/privacy') }}" class="group inline-flex items-center gap-1.5 transition-colors hover:text-mango"><span class="h-px w-0 bg-mango transition-all duration-300 group-hover:w-3"></span> Privacy Policy</a></li>
                </ul>
            </div>

            <!-- How it works -->
            <div>
                <h3 class="flex items-center gap-2.5 font-display text-[13px] font-bold uppercase tracking-[0.18em] text-white">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-gradient-to-br from-mango-soft to-mango-deep text-ink shadow-soft"><i class="bi bi-journals text-[10px]"></i></span> How It Works
                </h3>
                <div class="mt-3 h-px w-12 bg-gradient-to-r from-mango/60 to-transparent" aria-hidden="true"></div>
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

        <!-- Bottom bar -->
        <div class="mt-14 flex flex-col items-center justify-between gap-5 border-t border-white/10 pt-7 lg:flex-row">
            <p class="text-xs text-white/60">&copy; {{ date('Y') }} {{ storeName() }} — All rights reserved.</p>

            <div class="hidden flex-wrap items-center justify-center gap-2 md:flex">
                <span class="text-[10px] font-semibold uppercase tracking-[0.16em] text-mango">We accept</span>
                @foreach(['Paystack', 'Flutterwave', 'Kora', 'Verve', 'Visa', 'Mastercard'] as $pay)
                <span class="glass-chip gap-1.5 text-[11px] transition-transform duration-300 hover:-translate-y-0.5 hover:text-white">
                    <i class="bi bi-patch-check-fill text-[9px]"></i> {{ $pay }}
                </span>
                @endforeach
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <span class="glass-chip gap-2"><span class="h-1.5 w-1.5 rounded-full bg-grass shadow-[0_0_8px_2px_rgba(47,158,68,0.5)]"></span> Secured payments</span>
                <span class="glass-chip gap-2"><span class="h-1.5 w-1.5 rounded-full bg-mango shadow-[0_0_8px_2px_rgba(245,166,35,0.5)]"></span> 256-bit SSL</span>
            </div>
        </div>
    </div>
</footer>