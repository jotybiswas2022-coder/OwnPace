@extends('frontend.layouts.store')
@section('title', 'Order Confirmed — '.storeName())

@section('content')

<section class="os-section flex min-h-[70vh] items-center">
    <div class="mx-auto w-full max-w-2xl px-4 sm:px-6">
        <div class="os-card relative overflow-hidden p-8 text-center sm:p-12" x-reveal>
            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-grass via-grass-deep to-grass" aria-hidden="true"></div>

            <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border-2 border-grass/30 bg-grass/10 text-4xl text-grass-deep" x-reveal="150">
                <i class="bi bi-check-circle-fill"></i>
            </span>

            <h1 class="mt-6 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Order confirmed!</h1>
            <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-slate sm:text-base">Thank you for your purchase. Your order has been placed successfully and we'll start processing it right away.</p>

            <dl class="mx-auto mt-8 max-w-sm space-y-0 divide-y divide-ink/5 rounded-xl bg-paper-deep/50 p-5 text-sm">
                <div class="flex items-center justify-between py-3">
                    <dt class="text-slate">Order ID</dt>
                    <dd class="font-mono font-semibold text-ink">#{{ $order->id }}</dd>
                </div>
                <div class="flex items-center justify-between py-3">
                    <dt class="text-slate">Total</dt>
                    <dd class="os-price font-semibold">₦{{ number_format($order->grand_total, 0) }}</dd>
                </div>
                <div class="flex items-center justify-between py-3">
                    <dt class="text-slate">Status</dt>
                    <dd class="font-semibold text-grass-deep">{{ ucfirst($order->status) }}</dd>
                </div>
            </dl>

            <div class="mt-8 flex flex-col items-center gap-3">
                <a href="{{ route('orders.show', $order) }}" class="os-btn os-btn-mango w-full sm:w-auto"><i class="bi bi-eye-fill"></i> View order</a>
                <a href="{{ url('/shop') }}" class="os-btn os-btn-ghost w-full sm:w-auto"><i class="bi bi-grid-fill"></i> Continue shopping</a>
            </div>
        </div>
    </div>
</section>

@endsection
