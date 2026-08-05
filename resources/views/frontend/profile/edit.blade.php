@extends('frontend.layouts.store')
@section('title', 'Edit Profile — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-gear-fill"></i> Settings</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Account settings</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Update your personal information and security.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">

        @if($errors->any())
        <div class="mb-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
            <p class="flex items-center gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the errors below.</p>
        </div>
        @endif

        <div class="os-card p-6 sm:p-8" x-reveal>
            <h2 class="flex items-center gap-2 font-display text-base font-bold text-ink"><i class="bi bi-pencil-fill text-mango-deep"></i> Edit personal information</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 grid gap-5 sm:grid-cols-2">
                @csrf
                <div>
                    <label for="os-prof-name" class="os-label">Full name</label>
                    <input id="os-prof-name" type="text" name="name" class="os-input {{ $errors->has('name') ? 'os-input-error' : '' }}" value="{{ old('name', auth()->user()->name) }}" placeholder="Your full name">
                    @error('name') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="os-prof-email" class="os-label">Email (read-only)</label>
                    <input id="os-prof-email" type="email" class="os-input opacity-60" value="{{ auth()->user()->email }}" disabled>
                </div>
                <div>
                    <label for="os-prof-phone" class="os-label">Phone (read-only)</label>
                    <input id="os-prof-phone" type="text" class="os-input opacity-60" value="{{ auth()->user()->phone ?? '—' }}" disabled placeholder="+234 801 234 5678">
                </div>
                <p class="flex items-start gap-2 text-xs text-slate sm:col-span-2">
                    <i class="bi bi-info-circle-fill mt-0.5 text-mango-ink"></i>
                    Email and phone are locked for security. Contact support to update them.
                </p>
                <div class="sm:col-span-2">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save changes</button>
                </div>
            </form>
        </div>

        <div class="os-card mt-6 p-6 sm:p-8" x-reveal="100">
            <h2 class="flex items-center gap-2 font-display text-base font-bold text-ink"><i class="bi bi-shield-lock-fill text-mango-deep"></i> Change password</h2>
            <form method="POST" action="{{ route('profile.password') }}" class="mt-6 grid gap-5 sm:grid-cols-3">
                @csrf
                <div>
                    <label for="os-pw-current" class="os-label">Current password</label>
                    <input id="os-pw-current" type="password" name="current_password" class="os-input {{ $errors->has('current_password') ? 'os-input-error' : '' }}" placeholder="••••••••">
                    @error('current_password') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="os-pw-new" class="os-label">New password</label>
                    <input id="os-pw-new" type="password" name="password" class="os-input {{ $errors->has('password') ? 'os-input-error' : '' }}" placeholder="••••••••">
                    @error('password') <p class="os-error-text">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="os-pw-confirm" class="os-label">Confirm new password</label>
                    <input id="os-pw-confirm" type="password" name="password_confirmation" class="os-input" placeholder="••••••••">
                </div>
                <div class="sm:col-span-3">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Update password</button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
