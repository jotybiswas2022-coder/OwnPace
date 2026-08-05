@extends('frontend.auth_layout')
@section('title', 'Reset Password')

@section('content')
<div class="os-card p-6 sm:p-8" x-data="{ showPw: false, showPw2: false }" x-reveal>
    <div class="text-center">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-unlock-fill"></i></span>
        <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink">Choose a new password</h1>
        <p class="mt-1 text-sm text-slate">Set a new password for your {{ storeName() }} account.</p>
    </div>

    @if($errors->any())
    <div class="mt-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
        <p class="flex items-start gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="os-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" class="os-input" required autofocus autocomplete="email">
            @error('email') <p class="os-error-text">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="os-label">New password</label>
            <div class="relative">
                <input id="password" :type="showPw ? 'text' : 'password'" name="password" class="os-input pr-11" placeholder="••••••••" required autocomplete="new-password">
                <button type="button" class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded-lg p-1.5 text-slate transition-colors hover:text-ink" @click="showPw = !showPw" :aria-label="showPw ? 'Hide password' : 'Show password'">
                    <i class="bi" :class="showPw ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
            @error('password') <p class="os-error-text">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password-confirm" class="os-label">Confirm new password</label>
            <div class="relative">
                <input id="password-confirm" :type="showPw2 ? 'text' : 'password'" name="password_confirmation" class="os-input pr-11" placeholder="••••••••" required autocomplete="new-password">
                <button type="button" class="absolute right-2.5 top-1/2 -translate-y-1/2 rounded-lg p-1.5 text-slate transition-colors hover:text-ink" @click="showPw2 = !showPw2" :aria-label="showPw2 ? 'Hide password' : 'Show password'">
                    <i class="bi" :class="showPw2 ? 'bi-eye-slash' : 'bi-eye'"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="os-btn os-btn-mango w-full py-3.5 text-base"><i class="bi bi-check2-circle"></i> Reset password</button>
    </form>
</div>
@endsection
