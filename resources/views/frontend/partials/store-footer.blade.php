@php
use App\Models\Setting;
$settings = Setting::first();
$email = $settings?->email ?? 'support@'.strtolower(str_replace(' ', '', storeName())).'.com';
$phone = $settings?->phone ?? '+234 800 000 0000';
$location = $settings?->location ?? 'Lagos, Nigeria';
@endphp

<footer class="mt-20 border-t border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5" aria-label="{{ storeName() }} home">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand text-white">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2" opacity="0.45"/>
                            <circle cx="12" cy="12" r="4.5" fill="currentColor"/>
                        </svg>
                    </span>
                    <span class="font-display text-lg font-bold tracking-tight text-ink">{{ storeName() }}</span>
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate">
                    Nigeria's installment store. Own what you love today, and pay it down at your own pace — no surprises, no hidden fees.
                </p>
                <div class="mt-5 space-y-2 text-sm text-slate">
                    <p class="flex items-center gap-2"><span class="text-mango">●</span> {{ $location }}</p>
                    <p class="flex items-center gap-2"><span class="text-mango">●</span> {{ $phone }}</p>
                    <p class="flex items-center gap-2"><span class="text-mango">●</span> {{ $email }}</p>
                </div>
            </div>

            <!-- Shop -->
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-ink">Shop</h3>
                <ul class="mt-4 space-y-2.5 text-sm text-slate">
                    <li><a href="{{ url('/shop') }}" class="transition-colors hover:text-brand">All Products</a></li>
                    <li><a href="{{ url('/wishlist') }}" class="transition-colors hover:text-brand">Wishlist</a></li>
                    <li><a href="{{ url('/cart') }}" class="transition-colors hover:text-brand">Cart</a></li>
                    <li><a href="{{ url('/about') }}" class="transition-colors hover:text-brand">About Us</a></li>
                </ul>
            </div>

            <!-- Help -->
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-ink">Help</h3>
                <ul class="mt-4 space-y-2.5 text-sm text-slate">
                    <li><a href="{{ url('/faq') }}" class="transition-colors hover:text-brand">FAQs</a></li>
                    <li><a href="{{ url('/contact') }}" class="transition-colors hover:text-brand">Contact Us</a></li>
                    <li><a href="{{ url('/terms') }}" class="transition-colors hover:text-brand">Terms &amp; Conditions</a></li>
                    <li><a href="{{ url('/terms/payment') }}" class="transition-colors hover:text-brand">Payment Plans</a></li>
                    <li><a href="{{ url('/terms/delivery') }}" class="transition-colors hover:text-brand">Delivery Policy</a></li>
                    <li><a href="{{ url('/terms/privacy') }}" class="transition-colors hover:text-brand">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- How it works -->
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-ink">How It Works</h3>
                <div class="mt-4 space-y-3">
                    @php
                        $steps = [
                            ['01', 'Pick your product', 'browse and choose what you want'],
                            ['02', 'Choose a plan', 'weekly, bi-weekly or monthly'],
                            ['03', 'Pay it down', 'watch the ring fill as you pay'],
                        ];
                    @endphp
                    @foreach($steps as [$num, $title, $sub])
                    <div class="flex items-start gap-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-mango/15 font-mono text-xs font-bold text-mango-deep">{{ $num }}</span>
                        <div>
                            <p class="text-sm font-semibold text-ink">{{ $title }}</p>
                            @if(isset($sub))
                                <p class="text-xs text-slate">{{ $sub }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-ink/10 pt-6 sm:flex-row">
            <p class="text-xs text-slate">&copy; {{ date('Y') }} {{ storeName() }} — All rights reserved.</p>
            <div class="flex items-center gap-4">
                <span class="os-chip os-chip-grass"><span class="h-1.5 w-1.5 rounded-full bg-grass"></span> Secured payments</span>
                <span class="os-chip os-chip-brand"><span class="h-1.5 w-1.5 rounded-full bg-brand"></span> 256-bit SSL</span>
            </div>
        </div>
    </div>
</footer>
