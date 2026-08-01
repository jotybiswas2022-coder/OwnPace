@extends('frontend.app')
@section('title', $product->name . ' — OwnPace Store')

@section('content')
<section class="fp-prod-section">
    <div class="fp-prod-hero-bg">
        <div class="fp-prod-orb p1"></div>
        <div class="fp-prod-orb p2"></div>
    </div>

    <div class="container position-relative">
        <!-- Breadcrumb -->
        <nav class="fp-prod-breadcrumb reveal-up">
            <a href="{{ url('/') }}"><i class="bi bi-house-door-fill"></i></a>
            <i class="bi bi-chevron-right"></i>
            <a href="{{ url('/shop') }}">Shop</a>
            <i class="bi bi-chevron-right"></i>
            @if($product->category)
                <a href="{{ url('/shop?category_id='.$product->category_id) }}">{{ $product->category->name }}</a>
                <i class="bi bi-chevron-right"></i>
            @endif
            <span>{{ Str::limit($product->name, 30) }}</span>
        </nav>

        <div class="row g-5 mt-2">
            <!-- Images -->
            <div class="col-lg-6">
                <div class="fp-prod-gallery reveal-left">
                    <div class="fp-prod-main-img" id="fpMainImgWrap">
                        @php $mainImg = $product->primaryImage ?? $product->images->first(); @endphp
                        @if($mainImg)
                            <img src="{{ asset('storage/'.$mainImg->image_path) }}" alt="{{ $product->name }}" id="mainProductImg" fetchpriority="high" decoding="async">
                            <div class="fp-prod-img-zoom" id="imgZoomLens">
                                <i class="bi bi-arrows-fullscreen"></i>
                            </div>
                        @else
                            <div class="fp-no-image"><i class="bi bi-image"></i></div>
                        @endif
                        @if($product->installment_from)
                            <div class="fp-prod-img-badge">From ₦{{ number_format($product->installment_from, 0) }}/mo</div>
                        @endif
                        @if($product->compare_price && $product->compare_price > $product->price)
                            @php $disc = round((($product->compare_price - $product->price) / $product->compare_price) * 100); @endphp
                            @if($disc > 0)
                                <div class="fp-prod-img-discount">-{{ $disc }}%</div>
                            @endif
                        @endif
                    </div>
                    @if($product->images && $product->images->count() > 0)
                    <div class="fp-prod-thumbs">
                        @foreach($product->images as $img)
                        <div class="fp-prod-thumb {{ $img->is_primary ? 'active' : '' }}" onclick="changeImage(this, '{{ asset('storage/'.$img->image_path) }}')">
                            <img src="{{ asset('storage/'.$img->image_path) }}" alt="" loading="lazy" decoding="async">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="col-lg-6">
                <div class="fp-prod-info reveal-right">
                    <div class="fp-prod-tags">
                        <span class="fp-prod-brand"><i class="bi bi-building"></i> {{ $product->brand?->name ?? 'OwnPace' }}</span>
                        @if($product->category)
                            <span class="fp-prod-category">{{ $product->category->name }}</span>
                        @endif
                    </div>

                    <h1 class="fp-prod-title">{{ $product->name }}</h1>

                    <div class="fp-prod-rating">
                        @php $avgRating = $product->reviews->avg('rating') ?? 0; @endphp
                        <div class="fp-prod-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= round($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <span class="fp-prod-rating-text">{{ number_format($avgRating, 1) }} ({{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }})</span>
                    </div>

                    <div class="fp-prod-price-box">
                        <div class="fp-prod-current-price">₦{{ number_format($product->price, 0) }}</div>
                        @if($product->compare_price)
                            <div class="fp-prod-old-price">₦{{ number_format($product->compare_price, 0) }}</div>
                            @php $disc = round((($product->compare_price - $product->price) / $product->compare_price) * 100); @endphp
                            @if($disc > 0)
                                <div class="fp-prod-save-badge">Save {{ $disc }}%</div>
                            @endif
                        @endif
                    </div>

                    @if($product->installment_plans && $product->installment_plans->count() > 0)
                    <div class="fp-prod-plans">
                        <div class="fp-prod-plans-header">
                            <i class="bi bi-coin"></i>
                            <span>Available Payment Plans</span>
                        </div>
                        <div class="fp-prod-plans-list">
                            @foreach($product->installment_plans->take(4) as $plan)
                                @php
                                    $totalInterest = $product->price * ($plan->interest_rate / 100);
                                    $totalPayment = $product->price + $totalInterest;
                                    $installmentAmount = $totalPayment / $plan->duration;
                                @endphp
                                <div class="fp-prod-plan-item">
                                    <div class="fp-prod-plan-dur">{{ $plan->duration }} {{ $plan->duration_unit }}</div>
                                    <div class="fp-prod-plan-amount">₦{{ number_format($installmentAmount, 0) }}/{{ substr($plan->duration_unit, 0, -1) }}</div>
                                    <div class="fp-prod-plan-rate">{{ $plan->interest_rate }}% interest</div>
                                </div>
                            @endforeach
                            @if($product->installment_plans->count() > 4)
                                <div class="fp-prod-plan-item fp-prod-plan-more">+{{ $product->installment_plans->count() - 4 }} more plans</div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="fp-prod-desc">
                        <p>{{ Str::limit($product->description, 250) }}</p>
                        @if(strlen($product->description) > 250)
                            <a href="#description" class="fp-prod-readmore" onclick="document.querySelector('[href=\'#description\']').click(); return false;">Read full description <i class="bi bi-arrow-right"></i></a>
                        @endif
                    </div>

                    <div class="fp-prod-actions">
                        <form action="{{ route('cart.add') }}" method="POST" style="flex:1;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="fp-prod-cart-btn">
                                <i class="bi bi-cart-plus-fill"></i>
                                <span>Add to Cart</span>
                            </button>
                        </form>

                        @auth
                        <form action="{{ route('wishlist.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="fp-prod-wishlist-btn {{ $inWishlist ? 'active' : '' }}">
                                <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            </button>
                        </form>
                        @endauth

                        <button class="fp-prod-share-btn" onclick="navigator.share?.({title:'{{ $product->name }}',url:window.location.href})" title="Share">
                            <i class="bi bi-share-fill"></i>
                        </button>
                    </div>

                    <div class="fp-prod-trust">
                        <div class="fp-prod-trust-item">
                            <i class="bi bi-shield-fill-check"></i>
                            <div>
                                <strong>Secure Checkout</strong>
                                <small>256-bit SSL encrypted</small>
                            </div>
                        </div>
                        <div class="fp-prod-trust-item">
                            <i class="bi bi-arrow-repeat"></i>
                            <div>
                                <strong>Flexible Payments</strong>
                                <small>0% interest plans available</small>
                            </div>
                        </div>
                        <div class="fp-prod-trust-item">
                            <i class="bi bi-truck-front-fill"></i>
                            <div>
                                <strong>Fast Delivery</strong>
                                <small>Ship after 70% payment</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="fp-prod-tabs mt-5 reveal-up">
            <ul class="fp-prod-tab-nav" id="productTabs" role="tablist">
                <li role="none"><a class="fp-prod-tab-link active" data-tab="description" role="tab" aria-selected="true"><i class="bi bi-file-text-fill"></i> Description</a></li>
                <li role="none"><a class="fp-prod-tab-link" data-tab="plans" role="tab" aria-selected="false"><i class="bi bi-coin"></i> Payment Plans</a></li>
                <li role="none"><a class="fp-prod-tab-link" data-tab="reviews" role="tab" aria-selected="false"><i class="bi bi-star-fill"></i> Reviews ({{ $product->reviews->count() }})</a></li>
            </ul>
            <div class="fp-prod-tab-content">
                <div class="fp-prod-tab-pane active" id="tab-description" role="tabpanel">
                    <div class="fp-prod-tab-inner">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
                <div class="fp-prod-tab-pane" id="tab-plans" role="tabpanel">
                    <div class="fp-prod-tab-inner">
                        <h5 class="fp-prod-tab-title">Choose Your Installment Plan</h5>
                        <p class="fp-prod-tab-sub">Select a plan that fits your budget. All plans are subject to approval.</p>
                        @if($product->installment_plans && $product->installment_plans->count() > 0)
                        <div class="fp-prod-plan-grid">
                            @foreach($product->installment_plans as $plan)
                                @php
                                    $totalInterest = $product->price * ($plan->interest_rate / 100);
                                    $totalPayment = $product->price + $totalInterest;
                                    $installmentAmount = $totalPayment / $plan->duration;
                                @endphp
                                <div class="fp-prod-plan-card {{ $plan->interest_rate == 0 ? 'fp-prod-plan-featured' : '' }}">
                                    @if($plan->interest_rate == 0)
                                        <div class="fp-prod-plan-card-badge">Best Value</div>
                                    @endif
                                    <div class="fp-prod-plan-card-dur">{{ $plan->duration }} {{ $plan->duration_unit }}</div>
                                    <div class="fp-prod-plan-card-amount">₦{{ number_format($installmentAmount, 0) }}<span>/{{ substr($plan->duration_unit, 0, -1) }}</span></div>
                                    <div class="fp-prod-plan-card-total">Total: ₦{{ number_format($totalPayment, 0) }}</div>
                                    <div class="fp-prod-plan-card-rate">{{ $plan->interest_rate == 0 ? '0% Interest' : $plan->interest_rate.'% Interest' }}</div>
                                </div>
                            @endforeach
                        </div>
                        @else
                            <p style="color:var(--text-muted);">Payment plans will be available at checkout.</p>
                        @endif
                    </div>
                </div>
                <div class="fp-prod-tab-pane" id="tab-reviews" role="tabpanel">
                    <div class="fp-prod-tab-inner">
                        <div class="fp-prod-reviews-header">
                            <div>
                                <h5 class="fp-prod-tab-title">Customer Reviews</h5>
                                <p class="fp-prod-tab-sub">What our customers say about this product</p>
                            </div>
                            <div class="fp-prod-reviews-summary">
                                <div class="fp-prod-reviews-score">{{ number_format($avgRating, 1) }}</div>
                                <div class="fp-prod-reviews-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= round($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                    @endfor
                                </div>
                                <small>{{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }}</small>
                            </div>
                        </div>
                        @forelse($product->reviews as $review)
                        <div class="fp-prod-review">
                            <div class="fp-prod-review-avatar">{{ substr($review->user?->name ?? 'A', 0, 1) }}</div>
                            <div class="fp-prod-review-body">
                                <div class="fp-prod-review-head">
                                    <strong>{{ $review->user?->name ?? 'Anonymous' }}</strong>
                                    <div class="fp-prod-review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                    <small>{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <p>{{ $review->comment }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="fp-prod-review-empty">
                            <i class="bi bi-chat-square-text"></i>
                            <p>No reviews yet. Be the first to review this product!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts && $relatedProducts->count() > 0)
        <section class="mt-5 pt-4">
            <div class="section-head" style="text-align:left;">
                <div class="section-badge reveal-up"><i class="bi bi-link-45deg"></i> Related Products</div>
                <h2 class="reveal-up">You Might Also Like</h2>
            </div>
            <div class="row g-4">
                @foreach($relatedProducts as $i => $rp)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="fp-prod-related-card reveal-up" data-tilt="6" style="transition-delay:{{ $i * 0.06 }}s">
                        <a href="{{ url('/product/'.$rp->slug) }}" class="fp-prod-related-link">
                            <div class="fp-prod-related-img">
                                @php $rpImg = $rp->primaryImage ?? $rp->images->first(); @endphp
                                @if($rpImg)
                                    <img src="{{ asset('storage/'.$rpImg->image_path) }}" alt="{{ $rp->name }}" loading="lazy">
                                @else
                                    <div class="fp-prod-related-no-img"><i class="bi bi-image"></i></div>
                                @endif
                                @if($rp->installment_from)
                                    <span class="fp-prod-related-badge">₦{{ number_format($rp->installment_from, 0) }}/mo</span>
                                @endif
                            </div>
                            <div class="fp-prod-related-body">
                                @if($rp->brand)
                                    <span class="fp-prod-related-brand">{{ $rp->brand->name }}</span>
                                @endif
                                <h6>{{ Str::limit($rp->name, 40) }}</h6>
                                <div class="fp-prod-related-price">
                                    <span class="fp-prod-related-current">₦{{ number_format($rp->price, 0) }}</span>
                                    @if($rp->compare_price)
                                        <span class="fp-prod-related-old">₦{{ number_format($rp->compare_price, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</section>

@include('frontend.partials.footer')

<style>
/* ===== PRODUCT SECTION ===== */
.fp-prod-section {
    background: var(--near-black);
    padding: 32px 0 60px;
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}
.fp-prod-hero-bg {
    position: absolute; inset: 0; pointer-events: none;
    overflow: hidden;
}
.fp-prod-orb {
    position: absolute; border-radius: 50%; filter: blur(80px);
    animation: prodOrbFloat 10s ease-in-out infinite alternate;
}
.p1 { width: 350px; height: 350px; background: rgba(234,179,8,0.04); top: -100px; left: -50px; }
.p2 { width: 250px; height: 250px; background: rgba(234,179,8,0.03); bottom: -80px; right: 10%; animation-delay: 5s; }
@keyframes prodOrbFloat { 0%{transform:translate(0,0)scale(1)} 100%{transform:translate(30px,20px)scale(1.08)} }

/* Breadcrumb */
.fp-prod-breadcrumb {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--text-dim); flex-wrap: wrap;
    margin-bottom: 28px;
    padding: 10px 16px;
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: 10px;
}
.fp-prod-breadcrumb a { color: var(--gold-400); transition: color 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
.fp-prod-breadcrumb a:hover { color: var(--gold-300); }
.fp-prod-breadcrumb i { font-size: 11px; color: var(--card-border); }
.fp-prod-breadcrumb span { color: var(--text-muted); }

/* Gallery */
.fp-prod-gallery { position: sticky; top: 100px; }
.fp-prod-main-img {
    position: relative;
    background: linear-gradient(135deg, var(--card-dark), var(--dark-900));
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    overflow: hidden; height: 440px;
    display: flex; align-items: center; justify-content: center;
    transition: border-color 0.3s;
}
.fp-prod-main-img:hover { border-color: rgba(234,179,8,0.2); }
.fp-prod-main-img img { width: 100%; height: 100%; object-fit: contain; transition: transform 0.4s ease; }
.fp-prod-main-img:hover img { transform: scale(1.05); }
.fp-no-image { color: var(--card-border); font-size: 64px; }
.fp-prod-img-zoom {
    position: absolute; top: 12px; right: 12px;
    width: 38px; height: 38px; border-radius: 10px;
    background: rgba(0,0,0,0.5); color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; cursor: pointer;
    transition: all 0.3s; opacity: 0;
    backdrop-filter: blur(4px);
}
.fp-prod-main-img:hover .fp-prod-img-zoom { opacity: 1; }
.fp-prod-img-zoom:hover { background: var(--gold-500); color: var(--near-black); }
.fp-prod-img-badge {
    position: absolute; bottom: 12px; left: 12px; z-index: 2;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black);
    padding: 6px 14px; border-radius: 8px;
    font-weight: 700; font-size: 13px;
}
.fp-prod-img-discount {
    position: absolute; top: 12px; left: 12px; z-index: 2;
    background: #ef4444; color: white;
    padding: 4px 10px; border-radius: 6px;
    font-size: 12px; font-weight: 700;
}
.fp-prod-thumbs {
    display: flex; gap: 8px; margin-top: 12px;
    flex-wrap: wrap;
}
.fp-prod-thumb {
    width: 68px; height: 68px; border-radius: 10px;
    border: 2px solid var(--card-border); overflow: hidden;
    cursor: pointer; transition: all 0.3s;
    background: var(--card-dark);
    position: relative;
}
.fp-prod-thumb::after {
    content: ''; position: absolute; inset: 0;
    border-radius: 8px;
    border: 2px solid transparent;
    transition: all 0.3s;
}
.fp-prod-thumb img { width: 100%; height: 100%; object-fit: cover; }
.fp-prod-thumb.active { border-color: var(--gold-500); box-shadow: 0 0 12px rgba(234,179,8,0.15); }
.fp-prod-thumb:hover:not(.active) { border-color: rgba(234,179,8,0.3); }

/* Info */
.fp-prod-tags { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
.fp-prod-brand {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(234,179,8,0.1); color: var(--gold-400);
    padding: 5px 12px; border-radius: 6px;
    font-size: 12px; font-weight: 600;
}
.fp-prod-brand i { font-size: 11px; }
.fp-prod-category {
    display: inline-flex; align-items: center;
    background: var(--card-dark); color: var(--text-muted);
    border: 1px solid var(--card-border);
    padding: 5px 12px; border-radius: 6px;
    font-size: 12px; font-weight: 600;
}
.fp-prod-title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(24px, 3vw, 34px);
    font-weight: 800; color: var(--text-primary);
    margin-bottom: 12px; line-height: 1.15;
}
.fp-prod-rating {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 20px; flex-wrap: wrap;
}
.fp-prod-stars { display: flex; gap: 2px; }
.fp-prod-stars i { color: var(--gold-500); font-size: 15px; }
.fp-prod-rating-text { font-size: 13px; color: var(--text-muted); }

.fp-prod-price-box {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 20px;
    padding: 16px 20px;
    background: rgba(234,179,8,0.04);
    border: 1px solid rgba(234,179,8,0.12);
    border-radius: 12px;
}
.fp-prod-current-price {
    font-family: 'Syne', sans-serif;
    font-size: 34px; font-weight: 800; color: var(--gold-400);
    line-height: 1; white-space: nowrap;
}
.fp-prod-old-price {
    font-size: 18px; color: var(--text-dim); text-decoration: line-through;
    white-space: nowrap;
}
.fp-prod-save-badge {
    background: linear-gradient(135deg, #059669, #10b981);
    color: white;
    padding: 4px 10px; border-radius: 6px;
    font-size: 11px; font-weight: 700;
    margin-left: auto;
}

/* Plans */
.fp-prod-plans {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
}
.fp-prod-plans-header {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 700; color: var(--text-primary);
    margin-bottom: 12px;
}
.fp-prod-plans-header i { color: var(--gold-500); font-size: 16px; }
.fp-prod-plans-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 8px;
}
.fp-prod-plan-item {
    background: var(--surface-dark);
    border: 1px solid var(--card-border);
    border-radius: 10px;
    padding: 12px;
    text-align: center;
    transition: all 0.3s;
}
.fp-prod-plan-item:hover { border-color: rgba(234,179,8,0.2); }
.fp-prod-plan-dur { font-size: 13px; font-weight: 700; color: var(--text-primary); }
.fp-prod-plan-amount { font-size: 16px; font-weight: 800; color: var(--gold-400); font-family: 'Syne', sans-serif; margin: 4px 0; }
.fp-prod-plan-rate { font-size: 11px; color: var(--text-dim); }
.fp-prod-plan-more {
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-400); font-size: 12px; font-weight: 600;
    cursor: pointer;
}

.fp-prod-desc { color: var(--text-muted); font-size: 15px; line-height: 1.7; margin-bottom: 20px; overflow-wrap: break-word; }
.fp-prod-readmore {
    color: var(--gold-500); font-size: 13px; font-weight: 600;
    display: inline-flex; align-items: center; gap: 4px;
    text-decoration: none; margin-top: 4px;
    transition: gap 0.3s;
}
.fp-prod-readmore:hover { gap: 8px; color: var(--gold-400); }

/* Actions */
.fp-prod-actions {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 20px;
}
.fp-prod-cart-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); border: none;
    padding: 14px 28px; border-radius: 12px;
    font-weight: 700; font-size: 15px;
    cursor: pointer; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    font-family: inherit; position: relative; overflow: hidden;
}
.fp-prod-cart-btn::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, transparent 20%, rgba(255,255,255,0.15) 50%, transparent 80%);
    transform: translateX(-100%); transition: transform 0.6s;
}
.fp-prod-cart-btn:hover::before { transform: translateX(100%); }
.fp-prod-cart-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(234,179,8,0.25); }

.fp-prod-wishlist-btn {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    background: var(--card-dark); border: 1px solid var(--card-border);
    color: var(--text-dim); font-size: 20px; cursor: pointer;
    transition: all 0.3s; flex-shrink: 0;
}
.fp-prod-wishlist-btn:hover { border-color: rgba(239,68,68,0.3); color: #ef4444; background: rgba(239,68,68,0.05); }
.fp-prod-wishlist-btn.active { background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.3); }

.fp-prod-share-btn {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    background: var(--card-dark); border: 1px solid var(--card-border);
    color: var(--text-dim); font-size: 18px; cursor: pointer;
    transition: all 0.3s; flex-shrink: 0;
}
.fp-prod-share-btn:hover { border-color: rgba(234,179,8,0.3); color: var(--gold-400); }

/* Trust */
.fp-prod-trust {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
    padding: 16px;
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: 12px;
}
.fp-prod-trust-item {
    display: flex; align-items: center; gap: 10px;
}
.fp-prod-trust-item i { font-size: 20px; color: var(--gold-500); flex-shrink: 0; }
.fp-prod-trust-item strong { display: block; font-size: 12px; color: var(--text-primary); }
.fp-prod-trust-item small { font-size: 10px; color: var(--text-dim); }

/* Tabs */
.fp-prod-tabs {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    overflow: hidden; contain: layout style;
}
.fp-prod-tab-nav {
    display: flex; list-style: none; padding: 0; margin: 0;
    border-bottom: 1px solid var(--card-border);
    overflow-x: auto;
}
.fp-prod-tab-nav li { margin: 0; }
.fp-prod-tab-link {
    display: flex; align-items: center; gap: 6px;
    padding: 14px 24px;
    color: var(--text-muted); font-weight: 600; font-size: 13px;
    text-decoration: none; white-space: nowrap;
    border-bottom: 2px solid transparent;
    transition: all 0.3s; cursor: pointer;
    touch-action: manipulation;
}
.fp-prod-tab-link i { font-size: 15px; }
.fp-prod-tab-link:hover { color: var(--text-primary); }
.fp-prod-tab-link.active {
    color: var(--gold-500);
    border-bottom-color: var(--gold-500);
}
.fp-prod-tab-content { padding: 0; }
.fp-prod-tab-pane { display: none; padding: 28px 24px; }
.fp-prod-tab-pane.active { display: block; animation: tabFadeIn 0.4s ease; }
@keyframes tabFadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
.fp-prod-tab-inner { color: var(--text-muted); line-height: 1.8; font-size: 15px; }
.fp-prod-tab-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
.fp-prod-tab-sub { font-size: 14px; color: var(--text-dim); margin-bottom: 20px; }

/* Plan Grid in Tab */
.fp-prod-plan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 12px;
}
.fp-prod-plan-card {
    background: var(--surface-dark);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    position: relative;
    transition: all 0.3s;
}
.fp-prod-plan-card:hover { border-color: rgba(234,179,8,0.2); transform: translateY(-2px); }
.fp-prod-plan-featured {
    border-color: rgba(234,179,8,0.2);
    background: rgba(234,179,8,0.04);
}
.fp-prod-plan-card-badge {
    position: absolute; top: -1px; right: 16px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black);
    padding: 3px 10px; border-radius: 0 0 6px 6px;
    font-size: 10px; font-weight: 700;
}
.fp-prod-plan-card-dur { font-size: 15px; font-weight: 700; color: var(--text-primary); }
.fp-prod-plan-card-amount { font-size: 24px; font-weight: 800; color: var(--gold-400); font-family: 'Syne', sans-serif; margin: 8px 0; }
.fp-prod-plan-card-amount span { font-size: 14px; font-weight: 500; color: var(--text-dim); }
.fp-prod-plan-card-total { font-size: 13px; color: var(--text-muted); margin-bottom: 4px; }
.fp-prod-plan-card-rate { font-size: 12px; color: var(--text-dim); }

/* Reviews */
.fp-prod-reviews-header {
    display: flex; align-items: flex-start; justify-content: space-between;
    margin-bottom: 24px; gap: 20px; flex-wrap: wrap;
}
.fp-prod-reviews-summary {
    text-align: center;
    padding: 16px 24px;
    background: var(--surface-dark);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    min-width: 120px;
}
.fp-prod-reviews-score {
    font-family: 'Syne', sans-serif;
    font-size: 32px; font-weight: 800;
    background: linear-gradient(135deg, var(--gold-400), var(--gold-600));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}
.fp-prod-reviews-stars { color: var(--gold-500); font-size: 14px; margin: 4px 0; }
.fp-prod-reviews-summary small { color: var(--text-dim); font-size: 12px; }

.fp-prod-review {
    display: flex; gap: 14px;
    padding: 20px 0;
    border-bottom: 1px solid var(--card-border);
}
.fp-prod-review:last-child { border-bottom: none; }
.fp-prod-review-avatar {
    width: 42px; height: 42px; border-radius: 12px;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-700));
    color: var(--near-black);
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 16px;
    flex-shrink: 0;
}
.fp-prod-review-body { flex: 1; }
.fp-prod-review-head {
    display: flex; align-items: center; gap: 10px;
    flex-wrap: wrap; margin-bottom: 6px;
}
.fp-prod-review-head strong { font-size: 14px; color: var(--text-primary); }
.fp-prod-review-head small { font-size: 12px; color: var(--text-dim); margin-left: auto; }
.fp-prod-review-stars { color: var(--gold-500); font-size: 12px; display: flex; gap: 1px; }
.fp-prod-review-body p { color: var(--text-muted); font-size: 14px; line-height: 1.7; margin: 0; }

.fp-prod-review-empty {
    text-align: center; padding: 40px 20px;
}
.fp-prod-review-empty i { font-size: 40px; color: var(--text-dim); display: block; margin-bottom: 12px; }
.fp-prod-review-empty p { color: var(--text-muted); font-size: 14px; }

/* Related */
.fp-prod-related-card {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    height: 100%;
    will-change: transform;
}
.fp-prod-related-card:hover {
    border-color: rgba(234,179,8,0.3);
    box-shadow: 0 12px 48px rgba(234,179,8,0.08);
}
.fp-prod-related-link { display: block; text-decoration: none; height: 100%; }
.fp-prod-related-img {
    position: relative; height: 180px;
    background: var(--dark-900);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
.fp-prod-related-img::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.3));
    pointer-events: none;
}
.fp-prod-related-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
.fp-prod-related-card:hover .fp-prod-related-img img { transform: scale(1.08); }
.fp-prod-related-no-img { color: var(--card-border); font-size: 30px; }
.fp-prod-related-badge {
    position: absolute; bottom: 8px; left: 8px; z-index: 2;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); font-size: 11px; font-weight: 700;
    padding: 4px 10px; border-radius: 6px;
}
.fp-prod-related-body { padding: 12px 14px 14px; }
.fp-prod-related-brand {
    font-size: 11px; color: var(--gold-400); font-weight: 600;
    display: block; margin-bottom: 4px;
}
.fp-prod-related-body h6 {
    font-size: 14px; font-weight: 600; color: var(--text-primary);
    margin-bottom: 6px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    overflow: hidden; line-height: 1.4;
}
.fp-prod-related-price { display: flex; align-items: center; gap: 8px; }
.fp-prod-related-current { font-size: 15px; font-weight: 700; color: var(--gold-400); font-family: 'Syne', sans-serif; }
.fp-prod-related-old { font-size: 12px; color: var(--text-dim); text-decoration: line-through; }

/* ===== RESPONSIVE ===== */
@media (max-width: 991px) {
    .fp-prod-main-img { height: 320px; }
    .fp-prod-gallery { position: static; }
    .fp-prod-trust { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 576px) {
    .fp-prod-section { padding: 16px 0 40px; }
    .fp-prod-main-img { height: 260px; }
    .fp-prod-current-price { font-size: 28px; }
    .fp-prod-price-box { padding: 12px 16px; }
    .fp-prod-trust { grid-template-columns: 1fr; }
    .fp-prod-tab-link { padding: 12px 16px; font-size: 12px; }
    .fp-prod-tab-pane { padding: 20px 16px; }
    .fp-prod-plan-grid { grid-template-columns: 1fr 1fr; }
    .fp-prod-related-img { height: 150px; }
}
</style>

<script>
function changeImage(el, src) {
    document.getElementById('mainProductImg').src = src;
    document.querySelectorAll('.fp-prod-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

// Custom Tab System
document.querySelectorAll('.fp-prod-tab-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.fp-prod-tab-link').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.fp-prod-tab-pane').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
});
</script>
@endsection