@extends('frontend.layouts.store')
@section('title', 'Payment — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-lock-fill"></i> Secure payment</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Complete your payment</h1>
        <p class="mt-2 text-sm text-slate">Order #{{ $order->id }}</p>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-xl px-4 sm:px-6">
        <div class="os-card p-6 sm:p-8" x-reveal>
            <div class="text-center">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-credit-card-fill"></i></span>
                <h2 class="mt-4 font-display text-lg font-bold text-ink">Choose payment method</h2>
                <p class="mt-1 text-sm text-slate">Select your preferred payment gateway</p>
            </div>

            @php $pending = session('pending_payment'); @endphp
            <dl class="mt-6 rounded-xl bg-paper-deep/60 p-5 text-sm">
                <div class="flex items-center justify-between py-1.5">
                    <dt class="text-slate">Order amount</dt>
                    <dd class="os-price text-ink">₦{{ number_format($order->total_amount, 0) }}</dd>
                </div>
                <div class="flex items-center justify-between py-1.5">
                    <dt class="text-slate">Paid</dt>
                    <dd class="os-price text-grass-deep">₦{{ number_format($order->paid_amount ?? 0, 0) }}</dd>
                </div>
                @if($pending && $pending['amount'] > 0)
                    <div class="flex items-center justify-between py-1.5">
                        <dt class="text-slate">{{ $pending['label'] }}</dt>
                        <dd class="os-price text-ink">₦{{ number_format($pending['amount'], 2) }}</dd>
                    </div>
                    @if(!empty($pending['late_fee']))
                    <div class="flex items-center justify-between py-1.5">
                        <dt class="text-slate">Late fee</dt>
                        <dd class="os-price text-ember-deep">₦{{ number_format($pending['late_fee'], 2) }}</dd>
                    </div>
                    @endif
                @endif
                <div class="mt-1.5 flex items-center justify-between border-t border-ink/10 pt-3">
                    <dt class="font-display text-base font-bold text-ink">Due now</dt>
                    <dd class="os-price text-lg font-bold text-brand">₦{{ number_format($pending['amount'] ?? $order->remaining_amount, 2) }}</dd>
                </div>
            </dl>

            <form action="{{ route('payment.process', $order->id) }}" method="POST" class="mt-6">
                @csrf
                <fieldset class="grid gap-3">
                    <legend class="sr-only">Payment gateway selection</legend>
                    @php
                        $osGateways = [
                            ['value' => 'paystack', 'icon' => 'bi-credit-card-fill', 'name' => 'Paystack', 'desc' => 'Card, Bank, USSD'],
                            ['value' => 'flutterwave', 'icon' => 'bi-globe', 'name' => 'Flutterwave', 'desc' => 'Card, Bank, Mobile Money'],
                            ['value' => 'korapay', 'icon' => 'bi-shield-fill-check', 'name' => 'Korapay', 'desc' => 'Card, Bank Transfer, USSD'],
                        ];
                    @endphp
                    <div x-data="{ gateway: 'paystack' }">
                        @foreach($osGateways as $osGw)
                        <label class="mb-3 block cursor-pointer rounded-xl border-2 p-4 transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                               :class="gateway === '{{ $osGw['value'] }}' ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                            <input type="radio" name="gateway" value="{{ $osGw['value'] }}" class="sr-only" x-model="gateway" {{ $loop->first ? 'checked' : '' }}>
                            <div class="flex items-center gap-4">
                                <i class="bi {{ $osGw['icon'] }} text-2xl" :class="gateway === '{{ $osGw['value'] }}' ? 'text-mango-deep' : 'text-slate'"></i>
                                <span class="flex-1">
                                    <strong class="block text-sm text-ink">{{ $osGw['name'] }}</strong>
                                    <small class="text-xs text-slate">{{ $osGw['desc'] }}</small>
                                </span>
                                <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-colors"
                                      :class="gateway === '{{ $osGw['value'] }}' ? 'border-mango bg-mango' : 'border-ink/20'">
                                    <span class="h-2 w-2 rounded-full bg-white" x-show="gateway === '{{ $osGw['value'] }}'"></span>
                                </span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </fieldset>

                <button type="submit" class="os-btn os-btn-mango mt-4 w-full py-3.5 text-base"><i class="bi bi-lock-fill"></i> Pay now</button>
            </form>

            <div class="mt-5 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs text-slate">
                <span class="inline-flex items-center gap-1.5"><i class="bi bi-shield-fill-check text-grass-deep"></i> SSL encrypted</span>
                <span class="inline-flex items-center gap-1.5"><i class="bi bi-lock-fill text-brand"></i> 256-bit secure</span>
            </div>
        </div>
    </div>
</section>

@endsection
