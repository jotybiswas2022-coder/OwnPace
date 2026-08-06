@props([
    'product',
    'wishlistIds' => [],
    'badge' => null,      // optional string chip, e.g. "New"
])

@php
    $img = $product->primaryImage ?? $product->images->first();
    $wishlistIds = collect($wishlistIds)->all();
    $inWish = in_array($product->id, $wishlistIds);
    $lowStock = $product->stock_quantity !== null
        && $product->stock_quantity > 0
        && $product->stock_quantity < 5;
    $plansCount = $product->installment_plans_count ?? ($product->installmentPlans->count() ?? 0);
@endphp

<div class="pcard group relative">
    <a href="{{ url('/product/'.$product->slug) }}" class="absolute inset-0 z-[1] rounded-[1.25rem]" aria-label="{{ $product->name }}"></a>

    <div class="pcard-media">
        @if($img)
            <img src="{{ imageUrl($img->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="pcard-img">
        @else
            <div class="flex h-full w-full items-center justify-center text-4xl text-ink/20"><i class="bi bi-image"></i></div>
        @endif

        <span class="absolute left-3 top-3 flex flex-col items-start gap-2">
            @if($badge)
                <span class="os-chip os-chip-brand"><i class="bi bi-stars"></i> {{ $badge }}</span>
            @endif
            @if(($product->discount_percent ?? 0) > 0)
                <span class="os-chip os-chip-ember">-{{ $product->discount_percent }}%</span>
            @endif
        </span>

        @if($lowStock)
            <span class="absolute bottom-3 left-3 os-chip os-chip-ember"><i class="bi bi-exclamation-circle-fill"></i> Only {{ $product->stock_quantity }} left</span>
        @endif

        @auth
        <button
            type="button"
            class="wish-btn {{ $inWish ? 'is-active' : '' }}"
            data-wishlisted="{{ $inWish ? '1' : '0' }}"
            @click="toggleWishlist({{ $product->id }}, $el)"
            aria-label="{{ $inWish ? 'Remove from wishlist' : 'Add to wishlist' }}"
            title="{{ $inWish ? 'Remove from wishlist' : 'Add to wishlist' }}"
        >
            <i class="bi {{ $inWish ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        </button>
        @else
        <a href="{{ url('/login') }}" class="wish-btn" aria-label="Log in to save to wishlist" title="Log in to save">
            <i class="bi bi-heart"></i>
        </a>
        @endauth

        <span class="pcard-arrow"><i class="bi bi-arrow-right"></i></span>
    </div>

    <div class="flex flex-1 flex-col p-4">
        <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-ink transition-colors group-hover:text-brand">{{ Str::limit($product->name, 46) }}</h3>

        <div class="mt-2 flex items-baseline gap-2">
            <span class="money text-lg font-semibold text-brand">{{ formatPrice($product->price, 0) }}</span>
            @if($product->compare_price)
                <span class="money text-xs text-slate line-through">{{ formatPrice($product->compare_price, 0) }}</span>
            @endif
        </div>

        <div class="mt-3 flex items-center justify-between gap-2 border-t border-ink/5 pt-3">
            <div class="flex min-w-0 items-center gap-1.5">
                <span class="os-chip os-chip-brand shrink-0"><i class="bi bi-coin"></i> {{ $plansCount ?: 'Flexible' }} plans</span>
                @if($product->installment_from)
                    <span class="os-chip os-chip-grass hidden shrink-0 sm:inline-flex"><i class="bi bi-lightning-charge-fill"></i> from {{ currency() }}{{ number_format($product->installment_from, 0) }}/{{ $product->installment_type === 'weekly' ? 'wk' : 'mo' }}</span>
                @endif
            </div>
            <button
                type="button"
                class="qadd-btn relative z-[2]"
                @click="quickAdd({{ $product->id }}, $el)"
                aria-label="Add {{ $product->name }} to cart"
                title="Add to cart"
            >
                <i class="bi bi-cart-plus"></i>
            </button>
        </div>
    </div>
</div>
