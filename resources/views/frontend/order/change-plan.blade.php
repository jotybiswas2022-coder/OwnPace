@extends('frontend.layouts.store')
@section('title', 'Change Plan — Order #'.$order->id)

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
            <span class="font-semibold text-ink">Change plan</span>
        </nav>
        <div class="mt-4">
            <span class="os-eyebrow"><i class="bi bi-arrow-repeat"></i> Payment plan</span>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Request a new payment plan</h1>
            <p class="mt-2 text-sm text-slate">Pick a schedule that fits your budget — admin approval usually takes less than a day.</p>
        </div>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-4xl px-4 sm:px-6">

        @if(isset($pendingRequest) && $pendingRequest)
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-brand/20 bg-indigo/5 p-4" role="status">
            <i class="bi bi-hourglass-split mt-0.5 text-brand"></i>
            <p class="text-sm text-ink">You already have a <strong>pending plan change request</strong> for this order. It's waiting for admin review.</p>
        </div>
        @endif

        <div class="os-card p-6 sm:p-8" x-reveal>
            <div class="mb-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl bg-paper-deep/60 p-4">
                    <p class="os-label mb-1">Current plan</p>
                    <p class="text-sm font-semibold text-ink">{{ $order->installmentPlan?->name ?? '—' }}</p>
                </div>
                <div class="rounded-xl bg-paper-deep/60 p-4">
                    <p class="os-label mb-1">Remaining balance</p>
                    <p class="os-price text-sm">₦{{ number_format((float) $order->remaining_amount, 2) }}</p>
                </div>
                <div class="rounded-xl bg-paper-deep/60 p-4">
                    <p class="os-label mb-1">Cadence</p>
                    <p class="text-sm font-semibold text-ink">{{ $order->installmentPlan?->cadence ?? '—' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('orders.change.plan', $order) }}" id="planChangeForm" x-data="{ plan: {{ $plans->isNotEmpty() ? $plans->first()->id : 'null' }} }">
                @csrf

                <span class="os-label">Choose a new plan</span>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    @forelse($plans as $plan)
                    <label class="block cursor-pointer rounded-xl border-2 p-4 transition-all has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-mango/50"
                           :class="plan == {{ $plan->id }} ? 'border-mango bg-mango/5' : 'border-ink/10 bg-paper-deep/40 hover:border-mango/40'">
                        <input type="radio" name="requested_plan_id" value="{{ $plan->id }}" required class="sr-only" x-model="plan">
                        <div class="flex items-center gap-4">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-colors"
                                  :class="plan == {{ $plan->id }} ? 'border-mango bg-mango' : 'border-ink/20'">
                                <span class="h-2 w-2 rounded-full bg-white" x-show="plan == {{ $plan->id }}"></span>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="flex items-center gap-2 text-sm font-bold text-ink">
                                    {{ $plan->name }}
                                    <span class="text-[11px] font-normal text-slate">{{ $plan->duration }} {{ $plan->type }}</span>
                                </span>
                                <span class="block text-xs text-slate">{{ $plan->cadence }} · {{ $plan->duration }} installments</span>
                                @if($plan->description)
                                <span class="mt-1 block text-xs leading-relaxed text-slate/90">{{ Str::limit($plan->description, 80) }}</span>
                                @endif
                            </span>
                            <span class="os-chip os-chip-mango shrink-0">{{ $plan->interest_rate > 0 ? number_format((float) $plan->interest_rate, 1).'%' : '0%' }}</span>
                        </div>
                    </label>
                    @empty
                    <div class="sm:col-span-2">
                        <div class="flex flex-col items-center rounded-xl border border-dashed border-ink/15 bg-paper-deep/40 p-10 text-center">
                            <span class="os-empty-icon"><i class="bi bi-calendar-x"></i></span>
                            <p class="mt-4 max-w-sm text-sm text-slate">No other plans are available right now. Your current plan stays active — or reach out and we'll see what we can do.</p>
                            <a href="{{ url('/contact') }}" class="os-btn os-btn-brand os-btn-sm mt-5"><i class="bi bi-headset"></i> Contact support</a>
                        </div>
                    </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    <label for="os-plan-reason" class="os-label">Why do you want to change your plan?</label>
                    <textarea id="os-plan-reason" name="reason" rows="3" minlength="10" required class="os-input" placeholder="Tell us why you'd like a different duration (at least 10 characters)"></textarea>
                    @error('reason') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <button type="submit" class="os-btn os-btn-mango" {{ $plans->isEmpty() || (isset($pendingRequest) && $pendingRequest) ? 'disabled' : '' }}>
                        <i class="bi bi-send-fill"></i> Submit request
                    </button>
                    <a href="{{ route('orders.show', $order) }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back to order</a>
                </div>
                <p class="mt-4 text-xs text-slate">
                    <i class="bi bi-clock-history text-mango-deep"></i>
                    Your request stays pending until an admin approves or rejects it. Your current plan keeps working until then.
                </p>
            </form>
        </div>
    </div>
</section>

@endsection
