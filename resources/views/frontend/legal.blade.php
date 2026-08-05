@extends('frontend.layouts.store')
@section('title', 'Legal & Policies — '.storeName())

@php
    $osDocs = [
        ['bi-file-earmark-text-fill', 'mango', 'Terms & Conditions', 'The binding agreement that governs every purchase, installment plan, wallet and account on '.storeName().'.', url('/terms')],
        ['bi-credit-card-2-front-fill', 'brand', 'Payment Terms', 'How payments work — accepted methods, installment schedules, late payments, early settlement and refunds.', url('/terms/payment')],
        ['bi-truck-front-fill', 'grass', 'Delivery Policy', 'Dispatch thresholds, delivery windows, proxies, failed deliveries and what to do on damage.', url('/terms/delivery')],
        ['bi-shield-lock-fill', 'ember', 'Privacy Policy', 'What data we collect, how it powers your orders and wallet, and your rights over it.', url('/terms/privacy')],
    ];
@endphp

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-shield-lock-fill"></i> Legal</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Legal &amp; Policies</h1>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate sm:text-base">Everything you need to know about how {{ storeName() }} works, what you agree to when you shop, and how we protect your data.</p>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid gap-5 sm:grid-cols-2">
            @foreach($osDocs as [$icon, $tone, $title, $desc, $url])
                <a href="{{ $url }}" x-reveal="{{ $loop->index * 60 }}" class="os-card os-card-hover group flex flex-col p-7 sm:p-8">
                    <div class="flex items-start gap-5">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-xl
                            {{ $tone === 'mango' ? 'bg-mango/15 text-mango-deep' : ($tone === 'grass' ? 'bg-grass/15 text-grass-deep' : ($tone === 'ember' ? 'bg-ember/15 text-ember-deep' : 'bg-brand/10 text-brand')) }}">
                            <i class="bi {{ $icon }}" aria-hidden="true"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <h2 class="font-display text-lg font-bold text-ink transition-colors group-hover:text-brand">{{ $title }}</h2>
                            <p class="mt-1.5 text-sm leading-relaxed text-slate">{{ $desc }}</p>
                        </div>
                    </div>
                    <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-brand">
                        Read {{ $title }} <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </span>
                </a>
            @endforeach
        </div>

        <div class="os-card mx-auto mt-12 max-w-2xl p-8 text-center" x-reveal="120">
            <span class="os-empty-icon mx-auto"><i class="bi bi-chat-dots"></i></span>
            <h2 class="mt-4 font-display text-lg font-bold text-ink">Questions about a policy?</h2>
            <p class="mt-2 text-sm text-slate">Our support team is happy to clarify anything — reach out any time.</p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ url('/contact') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-headset"></i> Contact support</a>
                <a href="{{ url('/faq') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-question-circle"></i> Browse FAQs</a>
            </div>
        </div>
    </div>
</section>
@endsection