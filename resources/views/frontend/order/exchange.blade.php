@extends('frontend.layouts.store')
@section('title', 'Exchange Product — Order #'.$order->id)

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <nav class="flex flex-wrap items-center gap-2 text-sm text-slate" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="font-medium text-brand transition-colors hover:text-brand-deep">Home</a>
            <i class="bi bi-chevron-right text-xs" aria-hidden="true"></i>
            <a href="{{ route('orders.index') }}" class="font-medium text-brand transition-colors hover:text-brand-deep">Orders</a>
            <i class="bi bi-chevron-right text-xs" aria-hidden="true"></i>
            <a href="{{ route('orders.show', $order) }}" class="font-medium text-brand transition-colors hover:text-brand-deep">Order #{{ $order->id }}</a>
            <i class="bi bi-chevron-right text-xs" aria-hidden="true"></i>
            <span class="font-semibold text-ink">Exchange product</span>
        </nav>
        <div class="mt-4">
            <span class="os-eyebrow"><i class="bi bi-arrow-left-right"></i> Exchange</span>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Swap it for something you want more</h1>
            <p class="mt-2 text-sm text-slate">Pick a product from your wishlist to swap for — admin approval usually takes less than a day.</p>
        </div>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-4xl px-4 sm:px-6">

        @if(isset($pendingRequest) && $pendingRequest)
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-brand/20 bg-indigo/5 p-4" role="status">
            <i class="bi bi-hourglass-split mt-0.5 text-brand"></i>
            <p class="text-sm text-ink">You already have a <strong>pending exchange request</strong> for this order. It's waiting for admin review.</p>
        </div>
        @endif

        <div class="os-card p-6 sm:p-8" x-reveal>
            <span class="os-label">You'll swap</span>
            @if($currentProduct)
            <div class="mt-3 flex items-center gap-4 rounded-xl border border-ink/10 bg-paper-deep/40 p-4">
                @if($currentProduct->primaryImage)
                    <img src="{{ asset('storage/'.$currentProduct->primaryImage->image_path) }}" alt="{{ $currentProduct->name }}" class="h-12 w-12 shrink-0 rounded-lg object-cover ring-1 ring-ink/10">
                @else
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-paper-deep text-ink/20"><i class="bi bi-image"></i></span>
                @endif
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-ink">{{ $currentProduct->name }}</p>
                    <p class="text-xs text-slate">From your order — swap it for something from your wishlist</p>
                </div>
            </div>
            @else
            <div class="mt-3 rounded-xl border border-ember/25 bg-ember/5 p-4 text-sm text-ember-deep">
                <i class="bi bi-info-circle-fill"></i> We couldn't find the product on this order — please contact support.
            </div>
            @endif

            <form method="POST" action="{{ route('orders.exchange.request', $order) }}" id="exchangeForm" x-data="{ product: {{ $wishlist->isNotEmpty() ? $wishlist->first()->id : 'null' }} }">
                @csrf

                <span class="os-label mt-6 block">Choose a wishlist item to swap for</span>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    @forelse($wishlist as $product)
                    <label class="block cursor-pointer rounded-xl border-2 p-4 transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                           :class="product == {{ $product->id }} ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                        <input type="radio" name="product_id" value="{{ $product->id }}" required class="sr-only" x-model="product">
                        <div class="flex items-center gap-3">
                            @if($product->primaryImage)
                                <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}" alt="{{ $product->name }}" loading="lazy" class="h-14 w-14 shrink-0 rounded-lg object-cover ring-1 ring-ink/10">
                            @else
                                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-paper-deep text-ink/20"><i class="bi bi-image"></i></span>
                            @endif
                            <span class="min-w-0 flex-1">
                                <strong class="block truncate text-sm font-semibold text-ink">{{ Str::limit($product->name, 40) }}</strong>
                                <small class="text-xs text-slate">In your wishlist</small>
                            </span>
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-colors"
                                  :class="product == {{ $product->id }} ? 'border-mango bg-mango' : 'border-ink/20'">
                                <span class="h-2 w-2 rounded-full bg-white" x-show="product == {{ $product->id }}"></span>
                            </span>
                        </div>
                        <p class="os-price mt-2 text-sm">₦{{ number_format((float) $product->price, 0) }}</p>
                    </label>
                    @empty
                    <div class="sm:col-span-2">
                        <div class="flex flex-col items-center rounded-xl border border-dashed border-ink/15 bg-paper-deep/40 p-10 text-center">
                            <span class="os-empty-icon"><i class="bi bi-heartbreak-fill"></i></span>
                            <p class="mt-4 max-w-xs text-sm text-slate">Your wishlist is empty — add the product you'd like to swap for first.</p>
                            <a href="{{ url('/shop') }}" class="os-btn os-btn-brand os-btn-sm mt-5"><i class="bi bi-grid-fill"></i> Browse products</a>
                        </div>
                    </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    <label for="os-ex-reason" class="os-label">Why do you want to exchange?</label>
                    <textarea id="os-ex-reason" name="reason" rows="3" minlength="10" required class="os-input" placeholder="Tell us why you'd like to swap (at least 10 characters)"></textarea>
                    @error('reason') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <button type="submit" class="os-btn os-btn-mango" {{ $wishlist->isEmpty() || (isset($pendingRequest) && $pendingRequest) ? 'disabled' : '' }}>
                        <i class="bi bi-send-fill"></i> Submit exchange request
                    </button>
                    <a href="{{ route('orders.show', $order) }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back to order</a>
                </div>
                <p class="mt-4 text-xs text-slate">
                    <i class="bi bi-clock-history text-mango-deep"></i>
                    Your exchange stays pending until an admin approves it. Your current product stays with you until then.
                </p>
            </form>
        </div>
    </div>
</section>

@endsection
