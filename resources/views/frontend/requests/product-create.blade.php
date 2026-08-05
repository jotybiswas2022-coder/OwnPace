@extends('frontend.layouts.store')
@section('title', 'Request a Product — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-plus-square-fill"></i> Product request</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Request a product</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Can't find what you're looking for? Tell us and we'll try to stock it.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">

        @if($errors->any())
        <div class="mb-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
            <p class="flex items-center gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</p>
        </div>
        @endif

        {{-- Journey steps --}}
        <div class="flex items-center" x-reveal>
            @php
                $steps = [
                    ['label' => 'Submitted', 'icon' => 'bi-send-fill'],
                    ['label' => 'Under review', 'icon' => 'bi-search'],
                    ['label' => 'Approved / rejected', 'icon' => 'bi-check2-circle'],
                ];
            @endphp
            @foreach($steps as $i => $st)
            <div class="relative flex-1 text-center">
                @if($i < count($steps) - 1)
                <div class="absolute left-1/2 right-[-50%] top-[19px] h-0.5 bg-mango" aria-hidden="true"></div>
                @endif
                <span class="relative mx-auto flex h-10 w-10 items-center justify-center rounded-full text-base {{ $i === 0 ? 'bg-mango text-ink' : 'bg-paper-deep text-slate ring-1 ring-ink/15' }}">
                    <i class="bi {{ $st['icon'] }}"></i>
                </span>
                <p class="mt-2 text-[10px] font-semibold uppercase tracking-wider text-slate">{{ $st['label'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="os-card mt-8 p-6 sm:p-8" x-reveal="80">
            <h2 class="flex items-center gap-2 font-display text-base font-bold text-ink"><i class="bi bi-box-seam-fill text-mango-deep"></i> New product request</h2>
            <form method="POST" action="{{ route('requests.product.store') }}" class="mt-6 grid gap-5">
                @csrf
                <div>
                    <label for="os-pr-name" class="os-label">Product name <span class="font-normal normal-case text-slate">(required)</span></label>
                    <input id="os-pr-name" type="text" name="product_name" class="os-input" value="{{ old('product_name') }}" placeholder="e.g. Apple MacBook Air M3" required maxlength="255">
                    @error('product_name') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="os-pr-desc" class="os-label">Description</label>
                    <textarea id="os-pr-desc" name="description" rows="3" class="os-input" placeholder="Tell us a bit about the product — specs, features, model…" maxlength="2000">{{ old('description') }}</textarea>
                    @error('description') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="os-pr-url" class="os-label">Link</label>
                    <input id="os-pr-url" type="url" name="product_url" class="os-input" value="{{ old('product_url') }}" placeholder="https://example.com/product (optional)">
                    @error('product_url') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="os-pr-reason" class="os-label">Why do you want it?</label>
                    <textarea id="os-pr-reason" name="reason" rows="3" class="os-input" placeholder="Share why you'd love this product — we use this to prioritize requests (optional)" maxlength="1000">{{ old('reason') }}</textarea>
                    @error('reason') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="os-btn os-btn-mango"><i class="bi bi-send-fill"></i> Submit request</button>
                    <a href="{{ route('requests.index') }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back to requests</a>
                </div>
                <p class="text-xs text-slate">
                    <i class="bi bi-clock-history text-mango-deep"></i>
                    You'll see the status here as it moves from submitted → under review → approved or rejected.
                </p>
            </form>
        </div>
    </div>
</section>

@endsection
