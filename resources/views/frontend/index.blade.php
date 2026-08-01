@extends('frontend.layouts.store')
@section('title', storeName().' — Own at your own pace')

@section('content')

@if(session('success'))
<div class="bg-grass/10 border-l-4 border-grass text-grass px-4 py-3 text-sm" role="status">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="bg-ember/10 border-l-4 border-ember text-ember px-4 py-3 text-sm" role="status">{{ session('error') }}</div>
@endif

<!-- ===== HERO ===== -->
<section class="relative overflow-hidden bg-brand">
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -top-24 right-0 h-96 w-96 rounded-full bg-mango/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/4 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>
    </div>
    <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:py-28">
        <div class="text-white">
            <span class="os-chip os-chip-mango"><i class="bi bi-shield-fill-check"></i> 100% Secure — Flexible Installments</span>
            <h1 class="mt-5 font-display text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">
                Own what you love,<br>
                <span class="text-mango">at your own pace.</span>
            </h1>
            <p class="mt-5 max-w-md text-base leading-relaxed text-white/75 sm:text-lg">
                Shop thousands of products from trusted brands. Pick a weekly or monthly plan that fits your budget — and watch your balance shrink until it's fully yours.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ url('/shop') }}" class="os-btn os-btn-mango text-base"><i class="bi bi-grid-fill"></i> Start Shopping</a>
                <a href="{{ url('/register') }}" class="os-btn text-base text-white" style="border:1.5px solid rgba(255,255,255,0.35);"><i class="bi bi-person-plus"></i> Create Account</a>
            </div>
            <div class="mt-10 grid max-w-md grid-cols-3 gap-6">
                @php $stats = [['10k+','Customers'],['5k+','Products'],['4.8','Rating']]; @endphp
                @foreach($stats as [$val, $label])
                <div>
                    <p class="font-mono text-2xl font-semibold text-mango">{{ $val }}</p>
                    <p class="text-xs text-white/60">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Hero visual: signature progress ring -->
        <div class="flex justify-center lg:justify-end">
            <div class="rounded-2xl bg-white/5 p-8 ring-1 ring-white/10 backdrop-blur-sm">
                <div class="flex flex-col items-center gap-6">
                    <x-progress-ring :percentage="100" amount="₦" label="pay down" :size="160" :stroke="10" color="mango"/>
                    <div class="text-center">
                        <p class="font-display text-lg font-bold text-white">Every payment fills the ring.</p>
                        <p class="mt-1 text-sm text-white/60">When it's full, it's yours.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== TRUST BAR ===== -->
<section class="border-b border-ink/10 bg-white">
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-4 py-6 sm:px-6 lg:grid-cols-4">
        @php
            $trust = [
                ['bi-truck', 'Free delivery over ₦50,000'],
                ['bi-arrow-repeat', '30-day easy exchange'],
                ['bi-shield-check', '256-bit SSL payments'],
                ['bi-coin', '0% interest plans available'],
            ];
        @endphp
        @foreach($trust as [$icon, $text])
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi {{ $icon }}"></i></span>
            <p class="text-sm font-medium leading-snug text-ink">{{ $text }}</p>
        </div>
        @endforeach
    </div>
</section>

<!-- ===== FEATURED PRODUCTS ===== -->
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="os-eyebrow"><i class="bi bi-star-fill"></i> Featured</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Popular items you'll love</h2>
            </div>
            <a href="{{ url('/shop') }}" class="os-btn os-btn-ghost os-btn-sm">Browse all <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @forelse($featuredProducts ?? [] as $product)
            <a href="{{ url('/product/'.$product->slug) }}" class="os-card os-card-hover group flex flex-col overflow-hidden">
                <div class="relative aspect-square overflow-hidden bg-paper-deep">
                    @php $img = $product->primaryImage ?? $product->images->first(); @endphp
                    @if($img)
                        <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-4xl text-ink/20"><i class="bi bi-image"></i></div>
                    @endif
                    @if($product->compare_price && $product->compare_price > $product->price)
                        @php $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100); @endphp
                        @if($discount > 0)
                            <span class="absolute left-3 top-3 os-chip os-chip-ember">-{{ $discount }}%</span>
                        @endif
                    @endif
                </div>
                <div class="flex flex-1 flex-col p-4">
                    <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-ink">{{ Str::limit($product->name, 46) }}</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="font-mono text-lg font-semibold text-brand">{{ formatPrice($product->price, 0) }}</span>
                        @if($product->compare_price)
                            <span class="font-mono text-xs text-slate line-through">{{ formatPrice($product->compare_price, 0) }}</span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-center justify-between border-t border-ink/5 pt-3">
                        <span class="os-chip os-chip-brand"><i class="bi bi-coin"></i> {{ $product->installment_plans_count ?? ($product->installmentPlans->count() ?? 'Flexible') }} plans</span>
                        <x-progress-ring :percentage="25" amount="from" label="{{ $product->installment_from ? '₦'.number_format($product->installment_from, 0).'/mo' : '₦0/mo' }}" :size="44" :stroke="4" :animate="false"/>
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

<!-- ===== HOW IT WORKS ===== -->
<section class="os-section bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="text-center">
            <span class="os-eyebrow"><i class="bi bi-info-circle"></i> How it works</span>
            <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Three simple steps</h2>
            <p class="mx-auto mt-3 max-w-md text-slate">Get started in minutes — no paperwork, no delays.</p>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @php
                $steps = [
                    ['bi-hand-index-thumb', 'Choose your product', 'Browse thousands of items from trusted brands and find what you need.'],
                    ['bi-calendar-check', 'Pick your plan', 'Weekly, bi-weekly or monthly — a schedule that works for your budget.'],
                    ['bi-truck', 'Pay it down, get it now', 'A deposit ships your item immediately. Pay the rest at your own pace.'],
                ];
            @endphp
            @foreach($steps as [$icon, $title, $desc])
            <div class="os-card os-card-hover p-8 text-center">
                <div class="relative mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep">
                    <i class="bi {{ $icon }}"></i>
                    <span class="absolute -right-1.5 -top-1.5 flex h-6 w-6 items-center justify-center rounded-full bg-brand font-mono text-[11px] font-bold text-white">{{ $loop->iteration }}</span>
                </div>
                <h3 class="mt-5 font-display text-lg font-bold text-ink">{{ $title }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== WHY OWN PACE ===== -->
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div>
                <span class="os-eyebrow"><i class="bi bi-shield-check"></i> Why {{ storeName() }}</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Built around your balance</h2>
                <p class="mt-4 max-w-md leading-relaxed text-slate">We built {{ storeName() }} around one idea: things become yours when you pay them down. The Progress Ring on every plan shows exactly where you stand.</p>
                <ul class="mt-8 space-y-5">
                    @php
                        $features = [
                            ['bi-arrow-repeat', 'Flexible plans', 'Change your plan anytime, hassle-free.'],
                            ['bi-shield-check', 'Insurance', 'Protect items for just a fraction of the value.'],
                            ['bi-wallet2', 'Wallet', 'Fund your wallet and earn rewards as you go.'],
                            ['bi-headset', '24/7 support', 'Always here when you need us.'],
                        ];
                    @endphp
                    @foreach($features as [$icon, $title, $desc])
                    <li class="flex gap-4">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-lg text-brand"><i class="bi {{ $icon }}"></i></span>
                        <div>
                            <p class="font-semibold text-ink">{{ $title }}</p>
                            <p class="text-sm text-slate">{{ $desc }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative">
                <div class="rounded-2xl bg-white p-8 ring-1 ring-ink/10 shadow-lift">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate">Example plan — 60% paid off</p>
                            <p class="mt-1 font-display text-lg font-bold text-ink">Smartphone Pro X</p>
                        </div>
                        <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> On track</span>
                    </div>
                    <div class="mt-8 flex items-center justify-center">
                        <x-progress-ring :percentage="60" :amount="'₦' . number_format(132000, 0)" label="of ₦220,000" :size="150" :stroke="10"/>
                    </div>
                    <div class="mt-8 grid grid-cols-3 gap-4 text-center">
                        <div><p class="font-mono text-sm font-semibold text-ink">₦6,250</p><p class="text-[11px] text-slate">per week</p></div>
                        <div><p class="font-mono text-sm font-semibold text-ink">16 wks</p><p class="text-[11px] text-slate">remaining</p></div>
                        <div><p class="font-mono text-sm font-semibold text-grass">₦88,000</p><p class="text-[11px] text-slate">to go</p></div>
                    </div>
                </div>
                <div class="absolute -bottom-5 -left-5 -z-10 h-40 w-40 rounded-3xl bg-mango/20 blur-2xl" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</section>

<!-- ===== NEW ARRIVALS ===== -->
<section class="os-section bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <span class="os-eyebrow"><i class="bi bi-clock-history"></i> Just dropped</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">New arrivals</h2>
            </div>
        </div>
        <div class="mt-10 grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-4">
            @forelse($newArrivals ?? [] as $product)
            <a href="{{ url('/product/'.$product->slug) }}" class="os-card os-card-hover group flex flex-col overflow-hidden">
                <div class="relative aspect-square overflow-hidden bg-paper-deep">
                    @php $img = $product->primaryImage ?? $product->images->first(); @endphp
                    @if($img)
                        <img src="{{ asset('storage/'.$img->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-4xl text-ink/20"><i class="bi bi-image"></i></div>
                    @endif
                    <span class="absolute left-3 top-3 os-chip os-chip-brand">New</span>
                </div>
                <div class="flex flex-1 flex-col p-4">
                    <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-ink">{{ Str::limit($product->name, 46) }}</h3>
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

<!-- ===== TESTIMONIALS ===== -->
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="text-center">
            <span class="os-eyebrow"><i class="bi bi-chat-quote"></i> Testimonials</span>
            <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Paid off, and proud</h2>
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
            <figure class="os-card os-card-hover flex flex-col p-7">
                <div class="flex gap-1 text-mango" aria-label="{{ $rating }} out of 5 stars">
                    @for($s = 0; $s < 5; $s++)
                        <i class="bi {{ $s < $rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                </div>
                <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-ink/80">"{{ $text }}"</blockquote>
                <figcaption class="mt-6 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand font-display text-sm font-bold text-white">{{ substr($name, 0, 1) }}</span>
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

<!-- ===== CTA ===== -->
<section class="os-section-sm bg-brand">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-4 py-12 text-center sm:px-6 lg:flex-row lg:text-left">
        <div class="text-white">
            <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">Ready to own something new?</h2>
            <p class="mt-2 text-white/70">Create your free account in minutes. No credit check, no hidden fees, cancel anytime.</p>
        </div>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ url('/register') }}" class="os-btn os-btn-mango"><i class="bi bi-person-plus"></i> Create Free Account</a>
            <a href="{{ url('/shop') }}" class="os-btn text-white" style="border:1.5px solid rgba(255,255,255,0.35);"><i class="bi bi-grid-fill"></i> Browse Products</a>
        </div>
    </div>
</section>

@endsection
