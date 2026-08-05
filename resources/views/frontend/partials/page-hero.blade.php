{{--
    Page header band used at the top of most storefront pages.
    Props: eyebrow, title, description (optional), slot for right-side actions.
--}}
@props([
    'eyebrow' => null,
    'title' => null,
    'description' => null,
])

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-end justify-between gap-4 px-4 sm:px-6">
        <div class="max-w-2xl">
            @if($eyebrow)
                <span class="os-eyebrow"><i class="bi {{ $eyebrowIcon ?? 'bi-bookmark-star' }}"></i> {{ $eyebrow }}</span>
            @endif
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">{{ $title }}</h1>
            @if($description)
                <p class="mt-2 text-sm leading-relaxed text-slate sm:text-base">{{ $description }}</p>
            @endif
        </div>
        @isset($actions)
            <div class="flex flex-wrap items-center gap-3">{{ $actions }}</div>
        @endisset
    </div>
</section>
