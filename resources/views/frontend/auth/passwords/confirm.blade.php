@extends('frontend.auth_layout')
@section('title', 'Confirm Password')

@section('content')
<div class="os-card p-6 sm:p-8" x-data="{ showPw: false }" x-reveal>
    <div class="text-center">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-shield-fill-check"></i></span>
        <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink">Confirm your password</h1>
        <p class="mt-1 text-sm text-slate">Please confirm your password before continuing.</p>
    </div>

    @if($errors->any())
    <div class="mt-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
        <p class="flex items-start gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-6 space-y-5">
        @csrf
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

        <button type="submit" class="os-btn os-btn-mango w-full py-3.5 text-base"><i class="bi bi-check2-circle"></i> Confirm password</button>
    </form>

    @if (Route::has('password.request'))
    <p class="mt-5 text-center text-sm text-slate">
        Forgot your password?
        <a href="{{ route('password.request') }}" class="font-semibold text-brand transition-colors hover:text-brand-deep">Reset it</a>
    </p>
    @endif
</div>
@endsection
