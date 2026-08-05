@extends('frontend.layouts.store')
@section('title', $product->name . ' — '.storeName())

@php
    $mainImg = $product->primaryImage ?? $product->images->first();
    $plans = $product->installmentPlans->where('is_active', true)->sortBy('duration')->values();
    $lowest = null;
    if ($plans->count()) {
        foreach ($plans as $plan) {
            $lowest = $plan; // first (shortest duration) is the lowest per-payment
            break;
        }
    }
    $breakdown = $lowest ? \App\Services\InstallmentCalculatorService::breakdown($product->price, $lowest) : null;
    $avgRating = $product->reviews->avg('rating') ?? 0;
@endphp

@section('content')

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">

        <!-- Breadcrumb -->
        <nav class="mb-6 flex flex-wrap items-center gap-2 text-sm text-slate" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="font-medium transition-colors hover:text-brand"><i class="bi bi-house-door-fill"></i></a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ url('/shop') }}" class="font-medium transition-colors hover:text-brand">Shop</a>
            @if($product->category)
                <i class="bi bi-chevron-right text-xs"></i>
                <a href="{{ url('/shop?categories%5B0%5D='.$product->category_id) }}" class="font-medium transition-colors hover:text-brand">{{ $product->category->name }}</a>
            @endif
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-ink">{{ Str::limit($product->name, 30) }}</span>
        </nav>

        <div class="grid gap-10 lg:grid-cols-2" x-data="productPage()">
            <!-- ===== GALLERY ===== -->
            <div>
                <div class="relative overflow-hidden rounded-2xl border border-ink/10 bg-white" x-data="{ active: 0 }">
                    <div class="aspect-square bg-paper-deep">
                        <template x-for="(img, i) in @js($gallery)" :key="i">
                            <img
                                :src="img"
                                :alt="'{{ $product->name }}'"
                                x-show="active === i"
                                x-transition:enter="transition ease-out duration-200"
                                class="h-full w-full object-cover"
                            >
                        </template>
                        <template x-if="(@js($gallery)).length === 0">
                            <div class="flex h-full w-full items-center justify-center text-6xl text-ink/15"><i class="bi bi-image"></i></div>
                        </template>
                    </div>
                    @if($product->compare_price && $product->compare_price > $product->price)
                        @php $disc = round((($product->compare_price - $product->price) / $product->compare_price) * 100); @endphp
                        @if($disc > 0)
                            <span class="absolute left-4 top-4 os-chip os-chip-ember">-{{ $disc }}%</span>
                        @endif
                    @endif
                    @if($breakdown)
                        <span class="absolute bottom-4 left-4 os-chip os-chip-brand"><i class="bi bi-coin"></i> From {{ formatPrice($breakdown['per_installment'], 0) }}/{{ $lowest->type === 'weekly' ? 'wk' : 'mo' }}</span>
                    @endif
                </div>
                @if(count($gallery) > 1)
                <div class="mt-4 grid grid-cols-5 gap-3">
                    @foreach($gallery as $i => $img)
                    <button
                        type="button"
                        class="aspect-square overflow-hidden rounded-xl border-2 bg-white transition-all hover:border-brand/40"
                        :class="active === {{ $i }} ? 'border-brand' : 'border-ink/10'"
                        @click="active = {{ $i }}"
                        aria-label="Image {{ $i + 1 }}"
                    >
                        <img src="{{ $img }}" alt="" class="h-full w-full object-cover" loading="lazy">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- ===== INFO ===== -->
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    @if($product->brand)
                        <span class="os-chip os-chip-brand"><i class="bi bi-building"></i> {{ $product->brand->name }}</span>
                    @endif
                    @if($product->category)
                        <span class="os-chip">{{ $product->category->name }}</span>
                    @endif
                    @if($product->stock_quantity > 0)
                        <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> In stock</span>
                    @else
                        <span class="os-chip os-chip-ember"><i class="bi bi-hourglass-split"></i> Backorder</span>
                    @endif
                </div>

                <h1 class="mt-4 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">{{ $product->name }}</h1>

                <div class="mt-3 flex items-center gap-2 text-sm">
                    <span class="flex text-mango">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= round($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </span>
                    <span class="font-medium text-ink">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-slate">({{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }})</span>
                </div>

                <!-- ===== PAY ONCE vs INSTALLMENTS ===== -->
                <div class="mt-6 rounded-2xl border border-ink/10 bg-white p-5">
                    <div class="grid grid-cols-2 gap-2 rounded-xl bg-paper p-1.5" role="tablist" aria-label="Payment mode">
                        <button
                            type="button"
                            role="tab"
                            :aria-selected="mode === 'once'"
                            class="rounded-lg px-4 py-2.5 text-sm font-semibold transition-all"
                            :class="mode === 'once' ? 'bg-brand text-white shadow-soft' : 'text-slate hover:text-ink'"
                            @click="mode = 'once'"
                        ><i class="bi bi-cash-coin mr-1.5"></i>Pay Once</button>
                        <button
                            type="button"
                            role="tab"
                            :aria-selected="mode === 'installments'"
                            class="rounded-lg px-4 py-2.5 text-sm font-semibold transition-all"
                            :class="mode === 'installments' ? 'bg-brand text-white shadow-soft' : 'text-slate hover:text-ink'"
                            @click="mode = 'installments'"
                        ><i class="bi bi-coin mr-1.5"></i>Pay in Installments</button>
                    </div>

                    <!-- Pay Once -->
                    <div x-show="mode === 'once'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-5">
                        <div class="flex items-baseline gap-3">
                            <span class="font-mono text-4xl font-semibold text-brand">{{ formatPrice($product->price, 0) }}</span>
                            @if($product->compare_price)
                                <span class="font-mono text-lg text-slate line-through">{{ formatPrice($product->compare_price, 0) }}</span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-slate">One-time payment. No interest, no instalments — it's yours today.</p>
                    </div>

                    <!-- Installments -->
                    <div x-show="mode === 'installments'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-5">
                        @if($breakdown && $lowest)
                            <div class="flex items-center gap-5">
                                <x-progress-ring :percentage="0" :amount="formatPrice($breakdown['per_installment'], 0)" :label="'/'.$lowest->type" :size="96" :stroke="7" :animate="false"/>
                                <div>
                                    <p class="font-mono text-3xl font-semibold text-brand">{{ formatPrice($breakdown['per_installment'], 0) }}<span class="text-base text-slate"> /{{ $lowest->type === 'weekly' ? 'week' : 'month' }}</span></p>
                                    <p class="mt-1 text-sm text-slate">{{ $lowest->duration }} {{ $lowest->type === 'weekly' ? 'weekly' : 'monthly' }} payments · {{ $lowest->interest_rate > 0 ? $lowest->interest_rate.'% interest' : '0% interest' }} · total {{ formatPrice($breakdown['total'], 0) }}</p>
                                </div>
                            </div>
                            <p class="mt-4 text-xs text-slate"><i class="bi bi-info-circle-fill mr-1 text-brand"></i> Lowest weekly/monthly estimate shown — full plan calculation is applied at checkout.</p>
                        @else
                            <p class="text-sm text-slate">Installment plans will be available at checkout for this product.</p>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <h2 class="font-display text-sm font-bold uppercase tracking-[0.12em] text-ink">Description</h2>
                    <p class="mt-2 leading-relaxed text-slate">{{ $product->description ?? 'No description provided yet.' }}</p>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1 sm:flex-none">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="os-btn os-btn-brand w-full sm:w-auto">
                            <i class="bi bi-cart-plus-fill"></i> Add to Cart
                        </button>
                    </form>

                    @auth
                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="os-btn os-btn-ghost" aria-label="Toggle wishlist">
                            <i class="bi {{ $inWishlist ? 'bi-heart-fill text-ember' : 'bi-heart' }}"></i>
                        </button>
                    </form>
                    @endauth

                    <button type="button" class="os-btn os-btn-ghost" onclick="navigator.share?.({title:'{{ $product->name }}',url:window.location.href})" aria-label="Share">
                        <i class="bi bi-share-fill"></i>
                    </button>
                </div>

                <!-- Trust -->
                <div class="mt-6 grid grid-cols-3 gap-3">
                    <div class="rounded-xl border border-ink/10 bg-white p-3 text-center">
                        <i class="bi bi-shield-fill-check text-lg text-grass"></i>
                        <p class="mt-1 text-xs font-semibold text-ink">Secure Checkout</p>
                    </div>
                    <div class="rounded-xl border border-ink/10 bg-white p-3 text-center">
                        <i class="bi bi-arrow-repeat text-lg text-brand"></i>
                        <p class="mt-1 text-xs font-semibold text-ink">Flexible Plans</p>
                    </div>
                    <div class="rounded-xl border border-ink/10 bg-white p-3 text-center">
                        <i class="bi bi-truck-front-fill text-lg text-brand"></i>
                        <p class="mt-1 text-xs font-semibold text-ink">Delivered to You</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== PAYMENT PLANS ===== -->
        @if($plans->count())
        <div class="mt-14">
            <h2 class="font-display text-2xl font-bold tracking-tight text-ink">Payment plans</h2>
            <p class="mt-1 text-sm text-slate">Pick a pace that fits your budget.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($plans as $plan)
                    @php $bd = \App\Services\InstallmentCalculatorService::breakdown($product->price, $plan); @endphp
                    <div class="os-card p-5 {{ $plan->interest_rate == 0 ? 'border-brand/40 ring-2 ring-brand/20' : '' }}">
                        @if($plan->interest_rate == 0)
                            <span class="os-chip os-chip-brand mb-3"><i class="bi bi-stars"></i> Best Value</span>
                        @endif
                        <p class="font-display text-lg font-bold text-ink">{{ $plan->duration }} {{ $plan->type === 'weekly' ? 'weekly' : 'monthly' }} payments</p>
                        <p class="mt-2 font-mono text-3xl font-semibold text-brand">{{ formatPrice($bd['per_installment'], 0) }}<span class="text-sm text-slate"> /{{ $plan->type === 'weekly' ? 'wk' : 'mo' }}</span></p>
                        <p class="mt-2 text-xs text-slate">Total {{ formatPrice($bd['total'], 0) }} · {{ $plan->interest_rate > 0 ? $plan->interest_rate.'% interest' : '0% interest' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- ===== REVIEWS ===== -->
        <div class="mt-14">
            <h2 class="font-display text-2xl font-bold tracking-tight text-ink">Customer reviews</h2>
            @forelse($product->reviews as $review)
            <div class="mt-4 flex gap-4 rounded-2xl border border-ink/10 bg-white p-5">
                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-brand font-display text-sm font-bold text-white">{{ strtoupper(substr($review->user?->name ?? 'A', 0, 1)) }}</span>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <strong class="text-sm text-ink">{{ $review->user?->name ?? 'Anonymous' }}</strong>
                        <span class="flex text-mango">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }} text-xs"></i>
                            @endfor
                        </span>
                        <span class="text-xs text-slate">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="mt-1 text-sm text-slate">{{ $review->comment }}</p>
                </div>
            </div>
            @empty
            <div class="mt-4 flex flex-col items-center rounded-2xl border border-dashed border-ink/15 bg-white p-10 text-center">
                <span class="os-empty-icon"><i class="bi bi-chat-square-text"></i></span>
                <p class="mt-4 max-w-sm text-sm text-slate">No reviews yet — this product is fresh out of the box. Loved it? Paid it off? Your review helps the next buyer decide.</p>
                <div class="mt-5 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('orders.index') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-bag-check-fill"></i> Review from your orders</a>
                    <a href="{{ url('/shop') }}" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-grid-fill"></i> Browse more products</a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- ===== RELATED ===== -->
        @if($relatedProducts && $relatedProducts->count())
        <div class="mt-14">
            <h2 class="font-display text-2xl font-bold tracking-tight text-ink">You might also like</h2>
            <div class="mt-5 grid grid-cols-2 gap-4 sm:gap-6 xl:grid-cols-4">
                @foreach($relatedProducts as $rp)
                <a href="{{ url('/product/'.$rp->slug) }}" class="os-card os-card-hover group flex flex-col overflow-hidden">
                    <div class="aspect-square overflow-hidden bg-paper-deep">
                        @php $rpImg = $rp->primaryImage ?? $rp->images->first(); @endphp
                        @if($rpImg)
                            <img src="{{ imageUrl($rpImg->image_path) }}" alt="{{ $rp->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-3xl text-ink/15"><i class="bi bi-image"></i></div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-ink">{{ Str::limit($rp->name, 40) }}</h3>
                        <p class="mt-2 font-mono text-base font-semibold text-brand">{{ formatPrice($rp->price, 0) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
function productPage() {
    return {
        mode: 'once',
    };
}
</script>
@endpush
