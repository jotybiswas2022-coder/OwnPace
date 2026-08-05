@extends('frontend.layouts.store')
@section('title', 'My Cards — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-credit-card-fill"></i> Payment cards</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Saved cards</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Cards saved from your previous payments.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-5xl px-4 sm:px-6">
        @if(($cards ?? collect())->count() > 0)
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" x-reveal>
            @foreach($cards ?? [] as $card)
            <div class="os-card os-card-hover relative flex items-center gap-4 p-5">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand/10 text-xl text-brand"><i class="bi bi-credit-card-2-front-fill"></i></span>
                <div class="min-w-0 flex-1">
                    <p class="font-mono text-sm font-semibold text-ink">•••• {{ $card->card_number_last4 }}</p>
                    <p class="text-xs text-slate">Expires {{ $card->expiry_month }}/{{ $card->expiry_year }}</p>
                </div>
                <a href="{{ route('profile.cards.delete', $card) }}" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate transition-colors hover:bg-ember/10 hover:text-ember-deep" onclick="return confirm('Remove this card?')" aria-label="Remove card ending in {{ $card->card_number_last4 }}">
                    <i class="bi bi-trash-fill"></i>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="mx-auto max-w-lg" x-reveal>
            <x-frontend.partials.empty-state
                icon="bi-credit-card"
                title="No saved cards yet"
                message="Cards are saved automatically after your first payment — we never store your full card number."
                actionLabel="Browse products"
                actionUrl="{{ url('/shop') }}"
            />
        </div>
        @endif
    </div>
</section>

@endsection
