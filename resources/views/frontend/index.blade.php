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

    <div class="relative mx-auto grid max-w-7xl items-center gap-16 px-4 pb-32 pt-16 sm:px-6 lg:grid-cols-12 lg:gap-10 lg:pb-40 lg:pt-24">
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

            <div class="mt-9 flex flex-wrap items-center gap-4">
                <a href="{{ url('/shop') }}" class="os-shine os-btn os-btn-mango" style="padding:0.9rem 1.75rem;font-size:0.9375rem;">
                    <i class="bi bi-grid-fill"></i> Start Shopping
                </a>
                <a href="{{ url('/register') }}" class="os-shine glass-btn-ghost" style="font-size:0.9375rem;">
                    <i class="bi bi-person-plus"></i> Create Account
                </a>
            </div>

            <dl class="mt-12 grid max-w-md grid-cols-3 gap-6 border-t border-white/10 pt-8">
                <div>
                    <dt class="font-mono text-2xl font-semibold text-mango"><span x-countup="10000">10,000</span>+</dt>
                    <dd class="mt-1 text-xs text-white/55">Happy customers</dd>
                </div>
                <div>
                    <dt class="font-mono text-2xl font-semibold text-mango"><span x-countup="5000">5,000</span>+</dt>
                    <dd class="mt-1 text-xs text-white/55">Products in store</dd>
                </div>
                <div>
                    <dt class="font-mono text-2xl font-semibold text-mango">4.8<i class="bi bi-star-fill ml-1 align-middle text-sm"></i></dt>
                    <dd class="mt-1 text-xs text-white/55">Average rating</dd>
                </div>
            </dl>
        </div>

        <!-- Visual -->
        <div class="relative lg:col-span-6" x-reveal="140">
            <div class="pointer-events-none absolute -inset-10 -z-10 rounded-full" style="background: radial-gradient(closest-side, rgba(245,166,35,0.16), transparent 70%);" aria-hidden="true"></div>

            <div class="tilt-scene">
                <div class="glass-strong hero-card tilt-3d relative z-10 rounded-[1.75rem] p-7 sm:p-10">
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
                ['bi-truck', 'Free delivery over ₦50,000'],
                ['bi-arrow-repeat', '30-day easy exchange'],
                ['bi-shield-check', '256-bit SSL payments'],
                ['bi-coin', '0% interest plans'],
                ['bi-headset', '24/7 human support'],
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
                    <span class="block text-[11px] text-slate">Shop now</span>
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
            <a href="{{ url('/product/'.$product->slug) }}" x-reveal="{{ $loop->index * 60 }}" class="pcard group">
                <div class="pcard-media">
                    @php $img = $product->primaryImage ?? $product->images->first(); @endphp
                    @if($img)
                        <img src="{{ imageUrl($img->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="pcard-img">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-4xl text-ink/20"><i class="bi bi-image"></i></div>
                    @endif
                    @if($product->compare_price && $product->compare_price > $product->price)
                        @php $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100); @endphp
                        @if($discount > 0)
                            <span class="absolute left-3 top-3 os-chip os-chip-ember">-{{ $discount }}%</span>
                        @endif
                    @endif
                    <span class="pcard-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
                <div class="flex flex-1 flex-col p-4">
                    <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-ink transition-colors group-hover:text-brand">{{ Str::limit($product->name, 46) }}</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="font-mono text-lg font-semibold text-brand">{{ formatPrice($product->price, 0) }}</span>
                        @if($product->compare_price)
                            <span class="font-mono text-xs text-slate line-through">{{ formatPrice($product->compare_price, 0) }}</span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-ink/5 pt-3">
                        <span class="os-chip os-chip-brand"><i class="bi bi-coin"></i> {{ $product->installment_plans_count ?? 'Flexible' }} plans</span>
                        <x-progress-ring :percentage="25" amount="from" :label="$product->installment_from ? '₦'.number_format($product->installment_from, 0).'/mo' : '₦0/mo'" :size="44" :stroke="4" :animate="false"/>
                    </div>
                </div>
            </a>
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

<!-- ===================== NEW ARRIVALS ===================== -->
<section class="os-section bg-white border-y border-ink/5">
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
            <a href="{{ url('/product/'.$product->slug) }}" x-reveal="{{ $loop->index * 60 }}" class="pcard group">
                <div class="pcard-media">
                    @php $img = $product->primaryImage ?? $product->images->first(); @endphp
                    @if($img)
                        <img src="{{ imageUrl($img->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="pcard-img">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-4xl text-ink/20"><i class="bi bi-image"></i></div>
                    @endif
                    <span class="absolute left-3 top-3 os-chip os-chip-brand"><i class="bi bi-stars"></i> New</span>
                    @if($product->compare_price && $product->compare_price > $product->price)
                        @php $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100); @endphp
                        @if($discount > 0)
                            <span class="absolute right-3 top-3 os-chip os-chip-ember">-{{ $discount }}%</span>
                        @endif
                    @endif
                    <span class="pcard-arrow"><i class="bi bi-arrow-right"></i></span>
                </div>
                <div class="flex flex-1 flex-col p-4">
                    <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-ink transition-colors group-hover:text-brand">{{ Str::limit($product->name, 46) }}</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="font-mono text-lg font-semibold text-brand">{{ formatPrice($product->price, 0) }}</span>
                    </div>
                    <span class="os-chip os-chip-slate mt-3 w-fit"><i class="bi bi-coin"></i> Flexible plans</span>
                </div>
            </a>
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
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="text-center">
            <span class="os-eyebrow"><i class="bi bi-chat-quote"></i> Testimonials</span>
            <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Paid off, and proud</h2>
            <p class="mx-auto mt-3 max-w-md text-slate">Real people, real rings — fully filled.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @php
                $testimonials = [
                    ['Amara O.', 'Lagos', 'I got my dream laptop without breaking the bank. Watching the ring fill up each week kept me motivated — it was fully mine in four months.', 5],
                    ['Chidi E.', 'Abuja', 'Finally a platform that understands budgeting. The plan changed when I needed it, and the whole process felt honest.', 5],
                    ['Zainab K.', 'Kano', 'Delivery was faster than expected and the Progress Ring makes it satisfying to pay. I have recommended '.storeName().' to everyone.', 4],
                ];
            @endphp
            @foreach($testimonials as [$name, $city, $text, $rating])
            <figure x-reveal="{{ $loop->index * 80 }}" class="glass group relative rounded-3xl p-7 transition-all duration-300 hover:-translate-y-2 hover:border-mango/45 hover:shadow-lift">
                <i class="bi bi-quote absolute right-6 top-6 text-3xl text-brand/10 transition-colors duration-300 group-hover:text-mango/30"></i>
                <div class="flex gap-1 text-mango" aria-label="{{ $rating }} out of 5 stars">
                    @for($s = 0; $s < 5; $s++)
                        <i class="bi {{ $s < $rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                </div>
                <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-ink/80">"{{ $text }}"</blockquote>
                <figcaption class="mt-6 flex items-center gap-3 border-t border-ink/5 pt-5">
                    <span class="flex h-11 w-11 items-center justify-center rounded-full font-display text-sm font-bold text-white shadow-soft" style="background: linear-gradient(135deg, {{ $loop->index % 2 ? '#f5a623' : '#2e2a6b' }}, {{ $loop->index % 2 ? '#d98c0f' : '#4a4599' }});">{{ substr($name, 0, 1) }}</span>
                    <div>
                        <p class="text-sm font-semibold text-ink">{{ $name }}</p>
                        <p class="text-xs text-slate">{{ $city }}</p>
                    </div>
                </figcaption>
            </figure>
            @endforeach
        </div>
    </div>
</section>

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
