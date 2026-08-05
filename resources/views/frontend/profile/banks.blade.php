@extends('frontend.layouts.store')
@section('title', 'My Bank Accounts — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-end justify-between gap-4 px-4 sm:px-6">
        <div>
            <span class="os-eyebrow"><i class="bi bi-bank"></i> Bank accounts</span>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">My bank accounts</h1>
            <p class="mt-2 text-sm text-slate sm:text-base">Manage your saved bank accounts for withdrawals.</p>
        </div>
        <button type="button" class="os-btn os-btn-brand os-btn-sm" x-data @click="$dispatch('open-bank-modal')"><i class="bi bi-plus-lg"></i> Add bank</button>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section" x-data="{ modalOpen: false }" @open-bank-modal.window="modalOpen = true">
    <div class="mx-auto max-w-5xl px-4 sm:px-6">
        @if($errors->any())
        <div class="mb-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
            <p class="flex items-center gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the errors below.</p>
        </div>
        @endif

        @if(($banks ?? collect())->count() > 0)
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" x-reveal>
            @foreach($banks ?? [] as $bank)
            <div class="os-card os-card-hover relative p-5">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-bank2"></i></span>
                <h2 class="mt-3 text-sm font-bold text-ink">{{ $bank->bank_name }}</h2>
                <p class="os-price mt-1">•••• {{ substr($bank->account_number, -4) }}</p>
                <p class="mt-0.5 text-xs text-slate">{{ $bank->account_name }}</p>
                <a href="{{ route('profile.banks.delete', $bank) }}" class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-lg text-slate transition-colors hover:bg-ember/10 hover:text-ember-deep" onclick="return confirm('Remove this bank account?')" aria-label="Remove bank account ending in {{ substr($bank->account_number, -4) }}">
                    <i class="bi bi-trash-fill"></i>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="mx-auto max-w-lg" x-reveal>
            <div class="os-card flex flex-col items-center justify-center px-6 py-16 text-center">
                <span class="os-empty-icon"><i class="bi bi-bank"></i></span>
                <h3 class="mt-5 font-display text-lg font-bold text-ink">No bank accounts yet</h3>
                <p class="mt-2 max-w-sm text-sm leading-relaxed text-slate">Add a bank account to withdraw your wallet funds — a 10% processing fee applies.</p>
                <button type="button" class="os-btn os-btn-brand os-btn-sm mt-6" @click="modalOpen = true"><i class="bi bi-plus-lg"></i> Add bank account</button>
            </div>
        </div>
        @endif

        {{-- Add bank modal --}}
        <div x-cloak x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-[90] flex items-end justify-center bg-ink/60 p-0 backdrop-blur-sm sm:items-center sm:p-6" role="dialog" aria-modal="true" aria-label="Add bank account" @keydown.escape.window="modalOpen = false">
            <div class="w-full max-w-md rounded-t-2xl bg-white p-6 shadow-lift sm:rounded-2xl sm:p-7" @click.outside="modalOpen = false">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-display text-lg font-bold text-ink"><i class="bi bi-bank mr-2 text-mango-deep"></i> Add bank account</h3>
                    <button type="button" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate transition-colors hover:bg-paper-deep hover:text-ink" @click="modalOpen = false" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <form method="POST" action="{{ route('profile.banks.store') }}" class="grid gap-4">
                    @csrf
                    <div>
                        <label for="os-bank-name" class="os-label">Bank name</label>
                        <input id="os-bank-name" type="text" name="bank_name" class="os-input" placeholder="e.g. GTBank" required>
                    </div>
                    <div>
                        <label for="os-bank-acct-name" class="os-label">Account name</label>
                        <input id="os-bank-acct-name" type="text" name="account_name" class="os-input" placeholder="Name on the account" required>
                    </div>
                    <div>
                        <label for="os-bank-acct-number" class="os-label">Account number</label>
                        <input id="os-bank-acct-number" type="text" name="account_number" class="os-input" placeholder="10-digit account number" required>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" class="os-btn os-btn-ghost" @click="modalOpen = false">Cancel</button>
                        <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save bank account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
