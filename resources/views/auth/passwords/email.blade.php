@extends('frontend.auth_layout')
@section('title', 'Reset Password')

@section('content')
<div class="os-card p-6 sm:p-8" x-reveal>
    <div class="text-center">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-key-fill"></i></span>
        <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink">Reset your password</h1>
        <p class="mt-1 text-sm text-slate">Enter your email address and we'll send you a password reset link.</p>
    </div>

    @if (session('status'))
    <div class="mt-6 rounded-xl border border-grass/30 bg-grass/5 p-4 text-sm font-medium text-grass-deep" role="status">
        <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mt-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
        <p class="flex items-start gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
        @csrf
        <div>
            <label for="email" class="os-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="os-input" placeholder="you@example.com" required autofocus autocomplete="email">
            @error('email') <p class="os-error-text">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="os-btn os-btn-mango w-full py-3.5 text-base"><i class="bi bi-send-fill"></i> Send password reset link</button>
    </form>

    <p class="mt-5 text-center text-sm text-slate">
        Remembered it?
        <a href="{{ route('login') }}" class="font-semibold text-brand transition-colors hover:text-brand-deep">Back to login</a>
    </p>
</div>
@endsection
