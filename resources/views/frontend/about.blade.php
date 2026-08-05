@extends('frontend.layouts.store')
@section('title', 'About Us — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-info-circle-fill"></i> About Us</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Why {{ storeName() }}?</h1>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate sm:text-base">We're on a mission to make quality shopping accessible to everyone — pay at your own pace.</p>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div x-reveal>
                <span class="os-eyebrow"><i class="bi bi-bookmark-heart"></i> Our story</span>
                <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink">Buy now, pay comfortably</h2>
                <div class="mt-5 space-y-4 text-sm leading-relaxed text-slate sm:text-base">
                    <p>{{ storeName() }} is Nigeria's installment payment platform. We believe everyone deserves access to quality products without financial strain. Our platform lets you shop thousands of products and pay over time with flexible weekly or monthly plans that fit your budget.</p>
                    <p>With insurance coverage, a wallet system, delivery tracking and 24/7 support, we're here to make your shopping experience seamless and enjoyable. Our mission is simple: empower Nigerians to get what they need today while paying in a way that works for them.</p>
                    <p>Founded in 2025, we've quickly grown to serve thousands of happy customers across 36 states, partnering with top brands and trusted payment gateways to ensure a safe, secure shopping experience.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4" x-reveal="120">
                @php
                    $osStats = [
                        ['bi-box-seam', 5000, 'Products', 'text-mango-deep bg-mango/15'],
                        ['bi-emoji-smile', 15000, 'Happy Customers', 'text-brand bg-indigo/10'],
                        ['bi-coin', 36, 'Payment Plans', 'text-grass-deep bg-grass/10'],
                        ['bi-geo-alt', 36, 'States Covered', 'text-ember-deep bg-ember/10'],
                    ];
                @endphp
                @foreach($osStats as [$osIcon, $osCount, $osLabel, $osColor])
                <div class="os-card os-card-hover p-6 text-center">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl text-xl {{ $osColor }}"><i class="bi {{ $osIcon }}"></i></span>
                    <p class="mt-4 font-mono text-2xl font-bold text-ink" x-countup="{{ $osCount }}">0</p>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate">{{ $osLabel }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Mission / Vision --}}
        <div class="mt-16 grid gap-6 md:grid-cols-2" x-reveal>
            <div class="os-card relative overflow-hidden p-8">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-mango via-mango-deep to-mango" aria-hidden="true"></div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-mango/15 text-xl text-mango-deep"><i class="bi bi-bullseye"></i></span>
                <h3 class="mt-4 font-display text-lg font-bold text-ink">Our Mission</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate">To democratize access to quality products by providing flexible, transparent, and affordable installment payment solutions that empower Nigerians to shop without limits.</p>
            </div>
            <div class="os-card relative overflow-hidden p-8">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand via-brand-soft to-brand" aria-hidden="true"></div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/10 text-xl text-brand"><i class="bi bi-eye"></i></span>
                <h3 class="mt-4 font-display text-lg font-bold text-ink">Our Vision</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate">To become the most trusted and innovative installment payment platform in Africa, transforming the way millions of people shop and manage their finances.</p>
            </div>
        </div>
    </div>
</section>

{{-- Values --}}
<section class="os-section bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="text-center">
            <span class="os-eyebrow justify-center"><i class="bi bi-gem"></i> Our values</span>
            <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">What we stand for</h2>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @php
                $osValues = [
                    ['bi-shield-fill-check', 'Customer Trust', 'Transparency first', 'Always transparent about terms and fees, building lasting relationships through honesty and integrity.'],
                    ['bi-arrow-repeat', 'Flexibility', 'Adapt & thrive', 'Find the right plan that fits your budget — weekly, bi-weekly, or monthly. You choose what works.'],
                    ['bi-lightning-fill', 'Speed', 'Instant action', 'Process requests with an instant approval mindset — no delays, no unnecessary waiting.'],
                    ['bi-patch-check-fill', 'Reliability', 'Promise kept', 'Follow through on every delivery promise. We don't just commit — we deliver.'],
                    ['bi-gear-fill', 'Innovation', 'Always evolving', 'Constantly find better ways to serve our customers and simplify how they pay.'],
                    ['bi-people-fill', 'Teamwork', 'Together we win', 'Share knowledge, support teammates, and grow together to create exceptional experiences.'],
                ];
            @endphp
            @foreach($osValues as $osIndex => [$osIcon, $osTitle, $osTagline, $osText])
            <div class="os-card os-card-hover p-7 text-center" x-reveal="{{ $osIndex * 70 }}">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi {{ $osIcon }}"></i></span>
                <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.14em] text-slate">{{ $osTagline }}</p>
                <h3 class="mt-1 font-display text-base font-bold text-ink">{{ $osTitle }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate">{{ $osText }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Timeline --}}
<section class="os-section">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">
        <div class="text-center">
            <span class="os-eyebrow justify-center"><i class="bi bi-clock-history"></i> Our journey</span>
            <h2 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">How we started</h2>
        </div>
        <ol class="relative mt-12 space-y-8 border-l-2 border-ink/10 pl-8" x-reveal>
            @php
                $osMilestones = [
                    ['January 2025', 'We founded '.storeName().' with a vision to make quality products accessible to all Nigerians through flexible installment payments.'],
                    ['March 2025', 'Launched our beta platform with 500+ products across electronics, fashion, and home appliances.'],
                    ['June 2025', 'Reached 5,000 registered users and expanded the catalog to 2,000+ items from top brands.'],
                    ['Today', 'Serving thousands of happy customers with 5,000+ products, flexible payment plans, and nationwide delivery across all 36 states.'],
                ];
            @endphp
            @foreach($osMilestones as [$osDate, $osText])
            <li class="relative">
                <span class="absolute -left-[41px] top-1 flex h-4 w-4 items-center justify-center rounded-full bg-mango ring-4 ring-paper" aria-hidden="true">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                </span>
                <p class="font-mono text-sm font-bold text-mango-ink">{{ $osDate }}</p>
                <p class="mt-1 text-sm leading-relaxed text-slate">{{ $osText }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- CTA --}}
<section class="os-section-sm bg-brand">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-4 py-12 text-center sm:px-6 lg:flex-row lg:text-left">
        <div class="text-white">
            <h2 class="font-display text-2xl font-bold tracking-tight sm:text-3xl">Ready to start shopping?</h2>
            <p class="mt-2 text-white/70">Join thousands of Nigerians who shop flexibly with {{ storeName() }}.</p>
        </div>
        <a href="{{ url('/shop') }}" class="os-btn os-btn-mango"><i class="bi bi-grid-fill"></i> Browse Products</a>
    </div>
</section>

@endsection
