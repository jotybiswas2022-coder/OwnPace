@extends('frontend.layouts.store')
@section('title', storeName().' — Own at your own pace')

@section('content')

<!-- ===================== HERO ===================== -->
<section class="hero-dark relative isolate overflow-hidden text-white">
    <!-- Aurora field -->
    <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
        <div class="aurora-blob aurora-a"></div>
        <div class="aurora-blob aurora-b"></div>
        <div class="aurora-blob aurora-c"></div>
    </div>
    <div class="grain" aria-hidden="true"></div>

    <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 pb-24 pt-12 sm:px-6 sm:gap-16 sm:pb-32 sm:pt-14 lg:grid-cols-12 lg:gap-10 lg:pb-40 lg:pt-20">
        <!-- Copy -->
        <div class="lg:col-span-6" x-reveal>
            <span class="glass-chip"><i class="bi bi-shield-fill-check"></i> 100% Secure — Flexible Installments</span>

            <h1 class="mt-7 font-display text-4xl font-bold leading-[1.06] tracking-tight sm:text-5xl lg:text-[3.6rem]">
                Own what you love,<br>
                <span class="text-aurora">at your own pace.</span>
            </h1>

            <p class="mt-6 max-w-md text-base leading-relaxed text-white/70 sm:text-lg">
                Shop thousands of products from trusted brands. Pick a weekly or monthly plan that fits your budget — and watch your balance shrink until it's fully yours.
            </p>

            <!-- Search -->
            <form action="{{ url('/shop') }}" method="GET" class="mt-8 max-w-md" role="search">
                <div class="hero-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="text" name="search" placeholder="Search phones, laptops, fashion…" aria-label="Search products">
                    <button type="submit" class="os-btn os-btn-mango os-btn-sm">Search</button>
                </div>
            </form>

            <div class="mt-7 flex flex-wrap items-center gap-4">
                <a href="{{ url('/shop') }}" class="os-shine os-btn os-btn-mango" style="padding:0.9rem 1.75rem;font-size:0.9375rem;">
                    <i class="bi bi-grid-fill"></i> Start Shopping
                </a>
                <a href="{{ url('/register') }}" class="os-shine glass-btn-ghost" style="font-size:0.9375rem;">
                    <i class="bi bi-person-plus"></i> Create Account
                </a>
            </div>

            <dl class="mt-12 grid max-w-md grid-cols-3 gap-6 border-t border-white/10 pt-8">
                <div>
                    <dt class="font-mono text-2xl font-semibold text-mango"><span x-countup="{{ $stats['products'] }}">{{ number_format($stats['products']) }}</span>+</dt>
                    <dd class="mt-1 text-xs text-white/55">Products in store</dd>
                </div>
                <div>
                    <dt class="font-mono text-2xl font-semibold text-mango"><span x-countup="{{ $stats['categories'] }}">{{ number_format($stats['categories']) }}</span>+</dt>
                    <dd class="mt-1 text-xs text-white/55">Categories</dd>
                </div>
                <div>
                    <dt class="font-mono text-2xl font-semibold text-mango"><span x-countup="{{ $stats['brands'] }}">{{ number_format($stats['brands']) }}</span>+</dt>
                    <dd class="mt-1 text-xs text-white/55">Trusted brands</dd>
                </div>
            </dl>
        </div>

        <!-- Visual (desktop only — keeps the mobile hero clean) -->
        <div class="relative hidden lg:col-span-6 lg:block" x-reveal="140">
            <div class="pointer-events-none absolute -inset-10 -z-10 rounded-full" style="background: radial-gradient(closest-side, rgba(245,166,35,0.16), transparent 70%);" aria-hidden="true"></div>

            <div class="tilt-scene">
                <div class="glass-strong hero-card tilt-3d tilt-js relative z-10 rounded-[1.75rem] p-7 sm:p-10">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate">Featured plan</p>
                            <p class="mt-1 font-display text-xl font-bold text-ink">Smartphone Pro X</p>
                        </div>
                        <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> Paid in full</span>
                    </div>

                    <div class="mt-8 flex flex-col items-center gap-6">
                        <x-progress-ring :percentage="100" amount="₦220,000" label="paid in full" :size="172" :stroke="11" color="grass"/>
                        <div class="text-center">
                            <p class="font-display text-base font-bold text-ink">Every payment fills the ring.</p>
                            <p class="mt-1 text-sm text-slate">When it's full, it's yours.</p>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-3 gap-4 rounded-2xl bg-brand/5 p-4 text-center ring-1 ring-ink/5">
                        <div><p class="font-mono text-sm font-semibold text-ink">₦6,250</p><p class="mt-0.5 text-[11px] text-slate">per week</p></div>
                        <div><p class="font-mono text-sm font-semibold text-ink">24 wks</p><p class="mt-0.5 text-[11px] text-slate">total plan</p></div>
                        <div><p class="font-mono text-sm font-semibold text-grass">₦0</p><p class="mt-0.5 text-[11px] text-slate">to go</p></div>
                    </div>
                </div>
            </div>

            <!-- Floating mini toasts -->
            <div class="glass os-float-slow absolute -left-3 top-8 z-20 hidden items-center gap-3 rounded-2xl px-4 py-3 shadow-lift sm:flex lg:-left-10">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-grass/15 text-grass"><i class="bi bi-arrow-repeat"></i></span>
                <div>
                    <p class="text-xs font-bold text-ink">Plan changed</p>
                    <p class="text-[11px] text-slate">Weekly → Monthly</p>
                </div>
            </div>

            <div class="glass os-float-slower absolute -right-2 bottom-28 z-20 hidden items-center gap-3 rounded-2xl px-4 py-3 shadow-lift sm:flex lg:-right-8">
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-brand"><i class="bi bi-truck"></i></span>
                <div>
                    <p class="text-xs font-bold text-ink">On the way</p>
                    <p class="text-[11px] text-slate">Arrives in 2 days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll hint -->
    <a href="#shop" class="absolute bottom-7 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-white/40 transition-colors hover:text-white/80 lg:flex" aria-label="Scroll to explore">
        <span class="text-[10px] font-semibold uppercase tracking-[0.22em]">Explore</span>
        <span class="flex h-9 w-5 items-start justify-center rounded-full border border-current p-1">
            <span class="h-1.5 w-1 rounded-full bg-current animate-bounce"></span>
        </span>
    </a>
</section>

<!-- ===================== TRUST MARQUEE ===================== -->
<section class="relative z-10 -mt-14 pb-4 lg:-mt-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        @php
            $trustItems = [
                ['bi-truck', 'Free delivery over '.currency().'50,000'],
                ['bi-arrow-repeat', '30-day easy exchange'],
                ['bi-shield-check', '256-bit SSL payments'],
                ['bi-coin', '0% interest plans'],
                ['bi-headset', '24/7 human support'],
                ['bi-patch-check', 'Paystack · Flutterwave · Kora'],
            ];
            $marqueeItems = array_map(fn($t) => ['icon' => $t[0], 'text' => $t[1]], $trustItems);
            foreach (collect($brands ?? [])->pluck('name')->take(8) as $b) {
                $marqueeItems[] = ['icon' => 'bi-diamond-fill', 'text' => $b];
            }
        @endphp
        <div class="glass rounded-2xl py-4 shadow-soft">
            <div class="marquee px-4">
                <div class="marquee-track">
                    @for($copy = 0; $copy < 2; $copy++)
                    <div class="marquee-copy" @if($copy) aria-hidden="true" @endif>
                        @foreach($marqueeItems as $item)
                        <span class="flex items-center gap-2.5 whitespace-nowrap text-sm font-semibold text-slate">
                            <i class="bi {{ $item['icon'] }} text-mango-deep"></i> {{ $item['text'] }}
                        </span>
                        @endforeach
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== LIVE STAT BAND ===================== -->
<section class="os-section-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="stat-band px-6 py-9 sm:px-10" x-reveal>
            <div class="relative z-10 grid grid-cols-2 gap-8 text-center md:grid-cols-4">
                <div>
                    <p class="stat-num"><span x-countup="{{ $stats['products'] }}">{{ number_format($stats['products']) }}</span><small>+</small></p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-white/55">Products in store</p>
                </div>
                <div>
                    <p class="stat-num"><span x-countup="{{ $stats['categories'] }}">{{ number_format($stats['categories']) }}</span><small>+</small></p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-white/55">Categories</p>
                </div>
                <div>
                    <p class="stat-num"><span x-countup="{{ $stats['brands'] }}">{{ number_format($stats['brands']) }}</span><small>+</small></p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-white/55">Trusted brands</p>
                </div>
                <div>
                    <p class="stat-num"><span x-countup="{{ $stats['plans'] }}">{{ number_format($stats['plans']) }}</span><small>+</small></p>
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-white/55">Payment plans</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CATEGORIES ===================== -->
<section class="os-section-sm bg-white border-y border-ink/5">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="os-eyebrow"><i class="bi bi-grid-3x3-gap-fill"></i> Browse by category</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Shop the aisles</h2>
            </div>
            <a href="{{ url('/shop') }}" class="os-btn os-btn-ghost os-btn-sm">All products <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-3 lg:grid-cols-5">
            @php $catIcons = ['bi-phone', 'bi-laptop', 'bi-tv', 'bi-headphones', 'bi-watch', 'bi-camera', 'bi-speaker', 'bi-controller', 'bi-plug', 'bi-tablet']; @endphp
            @forelse($categories ?? [] as $category)
            <a href="{{ url('/shop?categories[0]='.$category->id) }}" x-reveal="{{ $loop->index * 40 }}" class="group flex items-center gap-3.5 rounded-2xl border border-ink/5 bg-white/70 p-4 shadow-soft backdrop-blur-md transition-all duration-300 hover:-translate-y-1.5 hover:border-mango/50 hover:shadow-lift">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-lg text-brand transition-all duration-300 group-hover:scale-110 group-hover:bg-mango group-hover:text-ink">
                    <i class="bi {{ $catIcons[$loop->index % count($catIcons)] }}"></i>
                </span>
                <span class="min-w-0">
                    <span class="block truncate font-display text-sm font-bold text-ink">{{ $category->name }}</span>
                    <span class="block text-[11px] text-slate">{{ $category->products_count ?? 0 }} {{ Str::plural('item', $category->products_count ?? 0) }}</span>
                </span>
                <i class="bi bi-arrow-right ml-auto shrink-0 text-slate transition-all duration-300 group-hover:translate-x-1 group-hover:text-brand"></i>
            </a>
            @empty
            <div class="col-span-full rounded-2xl border border-dashed border-ink/15 bg-white p-10 text-center">
                <i class="bi bi-grid-3x3-gap text-3xl text-ink/15"></i>
                <p class="mt-3 text-sm text-slate">Categories coming soon!</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ===================== FEATURED PRODUCTS ===================== -->
<section class="os-section" id="shop">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="os-eyebrow"><i class="bi bi-star-fill"></i> Featured</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Popular items you'll love</h2>
                <p class="mt-3 max-w-lg text-sm text-slate">Hand-picked favourites — start a plan today, pay it down at your own pace.</p>
            </div>
            <a href="{{ url('/shop') }}" class="os-btn os-btn-ghost os-btn-sm">Browse all <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @forelse($featuredProducts ?? [] as $product)
            <div x-reveal="{{ $loop->index * 60 }}">
                <x-frontend.pcard :product="$product" :wishlist-ids="$wishlistIds"/>
            </div>
            @empty
            <div class="col-span-full rounded-2xl border border-dashed border-ink/15 bg-white p-12 text-center">
                <i class="bi bi-box text-4xl text-ink/15"></i>
                <p class="mt-3 text-sm text-slate">Featured products coming soon!</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ===================== HOW IT WORKS ===================== -->
<section class="os-section bg-white border-y border-ink/5">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="text-center">
            <span class="os-eyebrow"><i class="bi bi-info-circle"></i> How it works</span>
            <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Three simple steps</h2>
            <p class="mx-auto mt-3 max-w-md text-slate">Get started in minutes — no paperwork, no delays.</p>
        </div>

        <div class="relative mt-14 grid gap-6 md:grid-cols-3">
            <div class="step-line hidden md:block" aria-hidden="true"></div>
            @php
                $steps = [
                    ['bi-hand-index-thumb', 'Choose your product', 'Browse thousands of items from trusted brands and find what you need.'],
                    ['bi-calendar-check', 'Pick your plan', 'Weekly, bi-weekly or monthly — a schedule that works for your budget.'],
                    ['bi-truck', 'Pay it down, get it now', 'A deposit ships your item immediately. Pay the rest at your own pace.'],
                ];
            @endphp
            @foreach($steps as [$icon, $title, $desc])
            <div x-reveal="{{ $loop->index * 80 }}" class="glass group relative rounded-3xl p-8 text-center transition-all duration-300 hover:-translate-y-2 hover:border-mango/45 hover:shadow-lift">
                <div class="relative mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-linear-to-br from-brand to-brand-soft text-2xl text-white shadow-soft transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                    <i class="bi {{ $icon }}"></i>
                    <span class="absolute -right-2 -top-2 flex h-7 w-7 items-center justify-center rounded-full bg-mango font-mono text-xs font-bold text-ink shadow-soft">{{ $loop->iteration }}</span>
                </div>
                <h3 class="mt-6 font-display text-lg font-bold text-ink">{{ $title }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== WHY + SIMULATOR ===================== -->
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid items-center gap-14 lg:grid-cols-2">
            <!-- Why -->
            <div x-reveal>
                <span class="os-eyebrow"><i class="bi bi-shield-check"></i> Why {{ storeName() }}</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Built around your balance</h2>
                <p class="mt-4 max-w-md leading-relaxed text-slate">We built {{ storeName() }} around one idea: things become yours when you pay them down. The Progress Ring on every plan shows exactly where you stand.</p>
                <ul class="mt-8 space-y-4">
                    @php
                        $features = [
                            ['bi-arrow-repeat', 'Flexible plans', 'Change your plan anytime, hassle-free.'],
                            ['bi-shield-check', 'Insurance', 'Protect items for just a fraction of the value.'],
                            ['bi-wallet2', 'Wallet', 'Fund your wallet and earn rewards as you go.'],
                            ['bi-headset', '24/7 support', 'Always here when you need us.'],
                        ];
                    @endphp
                    @foreach($features as [$icon, $title, $desc])
                    <li class="group flex gap-4">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-ink/5 bg-white/70 text-lg text-brand shadow-soft backdrop-blur-md transition-all duration-300 group-hover:scale-110 group-hover:bg-mango group-hover:text-ink">
                            <i class="bi {{ $icon }}"></i>
                        </span>
                        <div>
                            <p class="font-semibold text-ink">{{ $title }}</p>
                            <p class="text-sm text-slate">{{ $desc }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>

                <!-- Payment partners -->
                <div class="mt-8">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-slate">We partner with</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach(['Paystack', 'Flutterwave', 'Kora Pay', 'Verve', 'Visa', 'Mastercard'] as $pay)
                        <span class="pay-chip"><i class="bi bi-patch-check-fill"></i> {{ $pay }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Interactive simulator -->
            <div x-reveal="120" class="glass-strong relative overflow-hidden rounded-[1.75rem] p-7 sm:p-10"
                 x-data="{
                    total: 220000,
                    paid: 30000,
                    weekly: 6250,
                    get pct() { return Math.min(100, Math.round((this.paid / this.total) * 100)); },
                    get balance() { return this.total - this.paid; },
                    get weeksLeft() { return Math.max(0, Math.ceil(this.balance / this.weekly)); }
                 }">
                <div class="pointer-events-none absolute -right-16 -top-16 h-52 w-52 rounded-full bg-mango/15 blur-3xl" aria-hidden="true"></div>

                <div class="relative flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="os-eyebrow"><i class="bi bi-sliders"></i> Live demo</p>
                        <h3 class="mt-1 font-display text-xl font-bold text-ink">Pay it down, see it fill</h3>
                    </div>
                    <span class="os-chip os-chip-grass"><i class="bi bi-lightning-charge-fill"></i> Simulator</span>
                </div>

                <div class="relative mt-8 flex flex-col items-center gap-6">
                    <div class="relative">
                        <x-progress-ring :percentage="14" bound="pct" :size="176" :stroke="13"/>
                        <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
                            <span class="font-mono text-2xl font-semibold text-brand" x-text="'₦' + Number(balance).toLocaleString()"></span>
                            <span class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate">balance left</span>
                        </div>
                    </div>
                    <p class="text-center text-sm text-slate">Paid so far <strong class="font-mono text-ink" x-text="'₦' + Number(paid).toLocaleString()"></strong> of <strong class="font-mono text-ink" x-text="'₦' + Number(total).toLocaleString()"></strong></p>
                </div>

                <div class="relative mt-8">
                    <input type="range" min="0" :max="total" step="1250" x-model.number="paid"
                           class="sim-range" :style="'--fill:' + pct + '%'"
                           :aria-label="'Paid amount: ₦' + Number(paid).toLocaleString()">
                    <div class="mt-1 flex justify-between text-[11px] font-medium text-slate">
                        <span>₦0</span>
                        <span x-text="'₦' + Number(total).toLocaleString()">₦220,000</span>
                    </div>
                </div>

                <div class="relative mt-5 grid grid-cols-3 gap-3">
                    <button type="button" @click="paid = Math.min(total, paid + weekly)" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-plus-lg"></i> +1 week</button>
                    <button type="button" @click="paid = Math.min(total, paid + weekly * 4)" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-plus-lg"></i> +4 weeks</button>
                    <button type="button" @click="paid = total" class="os-btn os-btn-mango os-btn-sm"><i class="bi bi-check2-circle"></i> Pay off</button>
                </div>

                <div class="relative mt-6 flex items-center justify-between rounded-2xl bg-brand/5 px-5 py-4 ring-1 ring-ink/5">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate">Weeks left</p>
                        <p class="font-mono text-xl font-bold text-brand" x-text="weeksLeft"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-slate">Progress</p>
                        <p class="font-mono text-xl font-bold text-grass" x-text="pct + '%'"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== HOT DEALS ===================== -->
@if(($deals ?? collect())->count())
<section class="os-section bg-white border-y border-ink/5">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div>
                <span class="os-eyebrow"><i class="bi bi-fire"></i> This week only</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Hot deals you can't miss</h2>
                <p class="mt-3 max-w-lg text-sm text-slate">Snap up the season's biggest markdowns — on plans that fit your pocket.</p>
            </div>

            <div x-data="countdown()" class="flex items-center gap-3">
                <span class="hidden text-[11px] font-semibold uppercase tracking-[0.12em] text-slate sm:block">Ends in</span>
                <div class="flex gap-1.5">
                    <div class="countdown-box"><strong x-text="days">0</strong><span>days</span></div>
                    <div class="countdown-box"><strong x-text="hours">0</strong><span>hrs</span></div>
                    <div class="countdown-box"><strong x-text="minutes">0</strong><span>min</span></div>
                    <div class="countdown-box"><strong x-text="seconds">0</strong><span>sec</span></div>
                </div>
            </div>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @foreach($deals as $product)
            <div x-reveal="{{ $loop->index * 60 }}">
                <x-frontend.pcard :product="$product" :wishlist-ids="$wishlistIds" badge="Hot deal"/>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== NEW ARRIVALS ===================== -->
<section class="os-section @if(($deals ?? collect())->count()) bg-white border-t border-ink/5 @endif">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="os-eyebrow"><i class="bi bi-clock-history"></i> Just dropped</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">New arrivals</h2>
                <p class="mt-3 max-w-lg text-sm text-slate">Fresh stock, fresh plans — be the first to own it.</p>
            </div>
            <a href="{{ url('/shop') }}" class="os-btn os-btn-ghost os-btn-sm">See everything <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @forelse($newArrivals ?? [] as $product)
            <div x-reveal="{{ $loop->index * 60 }}">
                <x-frontend.pcard :product="$product" :wishlist-ids="$wishlistIds" badge="New"/>
            </div>
            @empty
            <div class="col-span-full rounded-2xl border border-dashed border-ink/15 bg-white p-12 text-center">
                <i class="bi bi-clock-history text-4xl text-ink/15"></i>
                <p class="mt-3 text-sm text-slate">New arrivals coming soon!</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ===================== TESTIMONIALS ===================== -->
@php
    $testimonials = !empty($homeTestimonials) ? $homeTestimonials : [
        ['name' => 'Amara O.', 'city' => 'Lagos', 'text' => 'I got my dream laptop without breaking the bank. Watching the ring fill up each week kept me motivated — it was fully mine in four months.', 'rating' => 5],
        ['name' => 'Chidi E.', 'city' => 'Abuja', 'text' => 'Finally a platform that understands budgeting. The plan changed when I needed it, and the whole process felt honest.', 'rating' => 5],
        ['name' => 'Zainab K.', 'city' => 'Kano', 'text' => 'Delivery was faster than expected and the Progress Ring makes it satisfying to pay. I have recommended '.storeName().' to everyone.', 'rating' => 4],
    ];
@endphp
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="text-center">
            <span class="os-eyebrow"><i class="bi bi-chat-quote"></i> Testimonials</span>
            <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Paid off, and proud</h2>
            <p class="mx-auto mt-3 max-w-md text-slate">Real people, real rings — fully filled.</p>
        </div>

        <div class="relative mx-auto mt-12 max-w-3xl" x-data="testimonialCarousel({{ count($testimonials) }})">
            <div class="relative">
                @foreach($testimonials as $i => $t)
                <figure
                    x-show="active === {{ $i }}"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-cloak
                    class="glass flex min-h-[16rem] flex-col rounded-3xl p-7 sm:min-h-[15rem] sm:p-9"
                >
                    <div class="flex gap-1 text-mango" aria-label="{{ $t['rating'] }} out of 5 stars">
                        @for($s = 0; $s < 5; $s++)
                            <i class="bi {{ $s < $t['rating'] ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                    <blockquote class="mt-4 flex-1 text-base leading-relaxed text-ink/85 sm:text-lg">"{{ $t['text'] }}"</blockquote>
                    <figcaption class="mt-6 flex items-center gap-3 border-t border-ink/5 pt-5">
                        <span class="flex h-11 w-11 items-center justify-center rounded-full font-display text-sm font-bold text-white shadow-soft" style="background: linear-gradient(135deg, {{ $loop->index % 2 ? '#f5a623' : '#2e2a6b' }}, {{ $loop->index % 2 ? '#d98c0f' : '#4a4599' }});">{{ substr($t['name'], 0, 1) }}</span>
                        <div>
                            <p class="text-sm font-semibold text-ink">{{ $t['name'] }}</p>
                            <p class="text-xs text-slate">{{ $t['city'] }}</p>
                        </div>
                    </figcaption>
                </figure>
                @endforeach
            </div>

            <button type="button" class="tst-nav absolute -left-3 top-1/2 z-10 -translate-y-1/2 sm:-left-16" @click="prev()" aria-label="Previous testimonial">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button type="button" class="tst-nav absolute -right-3 top-1/2 z-10 -translate-y-1/2 sm:-right-16" @click="next()" aria-label="Next testimonial">
                <i class="bi bi-chevron-right"></i>
            </button>

            <div class="mt-8 flex justify-center gap-2">
                @foreach($testimonials as $i => $t)
                <button type="button" class="tst-dot" :class="{ 'is-active': active === {{ $i }} }" @click="active = {{ $i }}" :aria-label="'Show testimonial ' + ({{ $i }} + 1)"></button>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- ===================== QUICK ANSWERS ===================== -->
@if(($faqs ?? collect())->count())
<section class="os-section bg-white border-y border-ink/5">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="os-eyebrow"><i class="bi bi-question-circle"></i> Quick answers</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Questions, answered</h2>
                <p class="mt-3 max-w-lg text-sm text-slate">The things everyone asks before their first plan.</p>
            </div>
            <a href="{{ url('/faq') }}" class="os-btn os-btn-ghost os-btn-sm">All FAQs <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="mx-auto mt-9 max-w-3xl space-y-3">
            @foreach($faqs as $faq)
            <div class="faq-item" :class="open && 'is-open'" x-data="{ open: false }">
                <button type="button" class="flex w-full items-center justify-between gap-4 px-5 py-4 text-left" @click="open = !open" :aria-expanded="open.toString()">
                    <span class="font-display text-sm font-bold text-ink sm:text-base">{{ $faq->question }}</span>
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-brand/5 text-brand transition-all duration-300" :class="open && 'rotate-45 bg-mango text-ink'">
                        <i class="bi bi-plus-lg text-sm"></i>
                    </span>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                    <p class="px-5 pb-5 text-sm leading-relaxed text-slate">{{ $faq->answer }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== CTA ===================== -->
<section class="os-section-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="cta-panel px-6 py-14 sm:px-14 sm:py-16" x-reveal>
            <div class="grain" aria-hidden="true"></div>
            <div class="relative z-10 flex flex-col items-center justify-between gap-8 text-center lg:flex-row lg:text-left">
                <div class="max-w-xl text-white">
                    <span class="glass-chip"><i class="bi bi-stars"></i> No credit check. No hidden fees.</span>
                    <h2 class="mt-5 font-display text-3xl font-bold tracking-tight sm:text-4xl">Ready to own something new?</h2>
                    <p class="mt-3 text-white/70">Create your free account in minutes. Cancel anytime, adjust any plan.</p>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ url('/register') }}" class="os-shine os-btn os-btn-mango" style="padding:0.9rem 1.75rem;font-size:0.9375rem;"><i class="bi bi-person-plus"></i> Create Free Account</a>
                    <a href="{{ url('/shop') }}" class="os-shine glass-btn-ghost" style="font-size:0.9375rem;"><i class="bi bi-grid-fill"></i> Browse Products</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
