@extends('frontend.auth_layout')
@section('title', 'Sign In')

@section('content')
<div class="os-card p-6 sm:p-8" x-data="{ showPw: false }" x-reveal>
    <div class="text-center">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-person-circle"></i></span>
        <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink">Welcome back</h1>
        <p class="mt-1 text-sm text-slate">Sign in to manage your purchases and plans.</p>
    </div>

    @if($errors->any())
    <div class="mt-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
        <p class="flex items-start gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="os-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="os-input" placeholder="you@example.com" required autocomplete="email" autofocus>
            @error('email') <p class="os-error-text">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="os-label">Password</label>
            <div class="relative">
                <input id="password" :type="showPw ? 'text' : 'password'" name="password" class="os-input pr-11" placeholder="••••••••" required autocomplete="current-password">
                <button type="button" class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded-lg p-1.5 text-slate transition-colors hover:text-ink" @click="showPw = !showPw" :aria-label="showPw ? 'Hide password' : 'Show password'">
                    <i class="bi" :class="showPw ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            @error('password') <p class="os-error-text">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded accent-mango" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>
            @if(Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-brand transition-colors hover:text-brand-deep"><i class="bi bi-key-fill"></i> Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="os-btn os-btn-mango w-full py-3.5 text-base">
            <i class="bi bi-box-arrow-in-right"></i> Sign in
        </button>
    </form>

    <div class="mt-6 rounded-xl border border-mango/30 bg-mango/5 p-5 text-center">
        <p class="text-sm text-slate">New to {{ storeName() }}?</p>
        <a href="{{ route('register') }}" class="os-btn os-btn-brand os-btn-sm mt-3"><i class="bi bi-person-plus-fill"></i> Create free account</a>
    </div>

    <p class="mt-5 flex items-center justify-center gap-2 text-xs text-slate">
        <i class="bi bi-shield-fill-check text-grass-deep"></i> Secured · Verified · Encrypted
    </p>
</div>
@endsection
