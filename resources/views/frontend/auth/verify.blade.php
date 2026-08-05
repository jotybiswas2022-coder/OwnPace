@extends('frontend.auth_layout')
@section('title', 'Verify Email')

@section('content')
<div class="os-card p-6 sm:p-8 text-center" x-reveal>
    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-envelope-check-fill"></i></span>
    <h1 class="mt-4 font-display text-2xl font-bold tracking-tight text-ink">Verify your email</h1>
    <p class="mt-2 text-sm leading-relaxed text-slate">We sent a verification link to your email address. Please check your inbox and click the link to activate your account.</p>

    @if (session('resent'))
    <div class="mt-6 rounded-xl border border-grass/30 bg-grass/5 p-4 text-sm font-medium text-grass-deep" role="status">
        <i class="bi bi-check-circle-fill"></i> A fresh verification link has been sent to your email.
    </div>
    @endif

    <form method="POST" action="{{ route('verification.resend') }}" class="mt-6">
        @csrf
        <p class="text-sm text-slate">Didn't receive the email?</p>
        <button type="submit" class="os-btn os-btn-mango mt-3 w-full py-3.5 text-base"><i class="bi bi-send-fill"></i> Resend verification link</button>
    </form>

    <a href="{{ route('login') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-brand transition-colors hover:text-brand-deep"><i class="bi bi-arrow-left"></i> Back to login</a>
</div>
@endsection
