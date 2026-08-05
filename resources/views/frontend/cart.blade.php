@extends('frontend.layouts.store')
@section('title', 'Shopping Cart — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-end justify-between gap-4 px-4 sm:px-6">
        <div>
            <span class="os-eyebrow"><i class="bi bi-cart-fill"></i> Shopping Cart</span>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Your cart</h1>
        </div>
        <a href="{{ url('/shop') }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-arrow-left"></i> Continue shopping</a>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        @if(isset($cart) && count($cart) > 0)
            @php $total = 0; @endphp
            <div class="grid gap-8 lg:grid-cols-3">
                {{-- Items --}}
                <div class="space-y-4 lg:col-span-2" x-reveal>
                    @foreach($cart as $item)
                        @php $subtotal = $item['price'] * ($item['quantity'] ?? 1); $total += $subtotal; @endphp
                        <div class="os-card flex flex-wrap items-center gap-4 p-4 sm:flex-nowrap sm:p-5" data-item-id="{{ $item['id'] }}">
                            <a href="{{ url('/product/'.$item['slug']) }}" class="block h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-paper-deep ring-1 ring-ink/10 transition-transform duration-200 hover:scale-[1.03]">
                                @if($item['thumbnail'])
                                    <img src="{{ asset('storage/'.$item['thumbnail']) }}" alt="{{ $item['name'] }}" loading="lazy" class="h-full w-full object-cover">
                                @else
                                    <span class="flex h-full w-full items-center justify-center text-2xl text-ink/20"><i class="bi bi-image"></i></span>
                                @endif
                            </a>

                            <div class="min-w-0 flex-1">
                                <a href="{{ url('/product/'.$item['slug']) }}" class="line-clamp-2 text-sm font-semibold leading-snug text-ink transition-colors hover:text-brand">{{ $item['name'] }}</a>
                                <p class="os-price mt-1 text-sm">₦{{ number_format($item['price'], 0) }}</p>
                                <p class="mt-1 text-xs text-slate sm:hidden">Subtotal: ₦{{ number_format($subtotal, 0) }}</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="flex items-center overflow-hidden rounded-lg ring-1 ring-ink/15" data-product-id="{{ $item['id'] }}">
                                    <button type="button" class="os-qty-minus flex h-9 w-9 items-center justify-center text-slate transition-colors hover:bg-mango/15 hover:text-mango-ink" data-action="decrease" aria-label="Decrease quantity of {{ $item['name'] }}"><i class="bi bi-dash"></i></button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] ?? 1 }}" min="1" max="99" class="os-qty-input w-11 border-0 text-center text-sm font-semibold text-ink focus:outline-none" readonly aria-label="Quantity of {{ $item['name'] }}">
                                    <button type="button" class="os-qty-plus flex h-9 w-9 items-center justify-center text-slate transition-colors hover:bg-mango/15 hover:text-mango-ink" data-action="increase" aria-label="Increase quantity of {{ $item['name'] }}"><i class="bi bi-plus"></i></button>
                                </div>
                                <p class="os-price hidden text-base sm:block" id="item-total-{{ $item['id'] }}">₦{{ number_format($subtotal, 0) }}</p>
                                <a href="{{ route('cart.remove', $item['id']) }}" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate transition-colors hover:bg-ember/10 hover:text-ember-deep" aria-label="Remove {{ $item['name'] }} from cart" title="Remove item"><i class="bi bi-trash"></i></a>
                            </div>
                        </div>
                    @endforeach

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                        <a href="{{ route('cart.clear') }}" class="os-btn os-btn-danger os-btn-sm" onclick="return confirm('Clear all items from your cart?')"><i class="bi bi-trash-fill"></i> Clear cart</a>
                        <p class="text-xs text-slate"><i class="bi bi-ticket-perforated"></i> Have a promo code? Apply it at checkout.</p>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="lg:sticky lg:top-24 lg:self-start" x-reveal="120">
                    <div class="os-card p-6">
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-receipt"></i></span>
                            <div>
                                <h2 class="font-display text-base font-bold text-ink">Order summary</h2>
                                <p class="text-xs text-slate">{{ count($cart) }} {{ Str::plural('item', count($cart)) }}</p>
                            </div>
                        </div>

                        <div class="os-hr"></div>

                        <dl class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate">Subtotal</dt>
                                <dd class="os-price text-ink">₦{{ number_format($total, 0) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate">Delivery fee</dt>
                                <dd class="text-xs font-semibold text-brand">Calculated at checkout</dd>
                            </div>
                            <div class="os-hr"></div>
                            <div class="flex items-center justify-between">
                                <dt class="font-display text-base font-bold text-ink">Estimated total</dt>
                                <dd class="os-price text-lg font-bold text-brand" id="cart-total">₦{{ number_format($total, 0) }}</dd>
                            </div>
                        </dl>

                        <a href="{{ route('checkout.index') }}" class="os-btn os-btn-mango mt-6 w-full py-3.5 text-base"><i class="bi bi-credit-card-fill"></i> Proceed to checkout</a>

                        <div class="mt-5 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs text-slate">
                            <span class="inline-flex items-center gap-1.5"><i class="bi bi-shield-fill-check text-grass-deep"></i> Secure checkout</span>
                            <span class="inline-flex items-center gap-1.5"><i class="bi bi-lock-fill text-brand"></i> Encrypted payment</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mx-auto max-w-lg" x-reveal>
                <x-frontend.partials.empty-state
                    icon="bi-cart-x"
                    title="Your cart is empty"
                    message="Looks like you haven't added anything yet. Browse the catalog and pick something you'll love."
                    actionLabel="Start shopping"
                    actionUrl="{{ url('/shop') }}"
                />
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-product-id]').forEach(control => {
    const input = control.querySelector('.os-qty-input');
    const minus = control.querySelector('.os-qty-minus');
    const plus = control.querySelector('.os-qty-plus');
    const productId = control.dataset.productId;

    function updateCart(qty) {
        input.value = qty;
        fetch('{{ route("cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ product_id: productId, quantity: qty }),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) { location.reload(); }
                else { window.flash(data.message || 'Could not update quantity', 'error'); }
            })
            .catch(() => window.flash('Could not update quantity', 'error'));
    }

    minus?.addEventListener('click', () => {
        const val = parseInt(input.value) || 1;
        if (val > 1) updateCart(val - 1);
    });

    plus?.addEventListener('click', () => {
        const val = parseInt(input.value) || 1;
        if (val < 99) updateCart(val + 1);
    });
});
</script>
@endpush
