@extends('frontend.layouts.store')
@section('title', 'My Wishlist — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-heart-fill"></i> My wishlist</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Saved items</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Items you love, ready when you are.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        @if(isset($wishlist) && $wishlist->count() > 0)
        <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:grid-cols-3 xl:grid-cols-4" x-reveal>
            @foreach($wishlist as $index => $item)
            @php $product = $item->product; @endphp
            @if($product)
            <div class="os-card os-card-hover group flex flex-col overflow-hidden" x-reveal="{{ min($index * 50, 200) }}">
                <a href="{{ url('/product/'.$product->slug) }}" class="relative block aspect-square overflow-hidden bg-paper-deep">
                    @if($product->primaryImage)
                        <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}" alt="{{ $product->name }}" loading="lazy" decoding="async" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <span class="flex h-full w-full items-center justify-center text-4xl text-ink/20"><i class="bi bi-image"></i></span>
                    @endif
                    @if($product->discount_percent)
                    <span class="os-chip os-chip-ember absolute left-3 top-3">-{{ $product->discount_percent }}%</span>
                    @endif
                    <span class="absolute inset-0 flex items-center justify-center bg-ink/40 opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                        <span class="os-btn os-btn-mango os-btn-sm"><i class="bi bi-eye-fill"></i> Quick view</span>
                    </span>
                </a>
                <div class="flex flex-1 flex-col p-4">
                    @if($product->category)
                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-mango-ink">{{ $product->category->name }}</p>
                    @endif
                    <h2 class="line-clamp-2 mt-1 text-sm font-semibold leading-snug text-ink">{{ Str::limit($product->name, 50) }}</h2>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="os-price text-base">{{ formatPrice($product->price, 0) }}</span>
                        @if($product->old_price)
                        <span class="font-mono text-xs text-slate line-through">{{ formatPrice($product->old_price, 0) }}</span>
                        @endif
                    </div>
                    @if($product->installment_price)
                    <p class="mt-1 text-xs text-slate">From <strong class="os-price text-mango-ink">{{ formatPrice($product->installment_price, 0) }}/mo</strong></p>
                    @endif
                    <div class="mt-3 flex gap-2 border-t border-ink/5 pt-3">
                        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="os-btn os-btn-mango os-btn-sm w-full"><i class="bi bi-cart-plus-fill"></i> Add to cart</button>
                        </form>
                        <form action="{{ route('wishlist.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-lg border border-ember/25 text-ember-deep transition-colors hover:bg-ember/10" aria-label="Remove {{ $product->name }} from wishlist"><i class="bi bi-heartbreak-fill"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @else
        <div class="mx-auto max-w-lg" x-reveal>
            <x-frontend.partials.empty-state
                icon="bi-heartbreak-fill"
                title="Your wishlist is empty"
                message="Save items you love to your wishlist and come back to them anytime."
                actionLabel="Browse products"
                actionUrl="{{ url('/shop') }}"
            />
        </div>
        @endif
    </div>
</section>

@endsection
