@extends('frontend.layouts.store')
@section('title', 'Verification — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-patch-check-fill"></i> Account verification</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">Verification status</h1>
        <p class="mt-2 text-sm text-slate sm:text-base">Complete your verification to unlock all features.</p>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section">
    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        @if($errors->any())
        <div class="mb-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
            <p class="flex items-center gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the errors below.</p>
        </div>
        @endif

        @php
            $vTypes = [
                ['key' => 'identity_card', 'icon' => 'bi-person-badge-fill', 'label' => 'Identity card'],
                ['key' => 'payment_card', 'icon' => 'bi-credit-card-2-front-fill', 'label' => 'Payment card'],
                ['key' => 'bank_account', 'icon' => 'bi-bank2', 'label' => 'Bank account'],
                ['key' => 'email', 'icon' => 'bi-envelope-fill', 'label' => 'Email address'],
                ['key' => 'store_terms', 'icon' => 'bi-file-earmark-text-fill', 'label' => 'Store terms'],
                ['key' => 'delivery_address', 'icon' => 'bi-geo-alt-fill', 'label' => 'Delivery address'],
            ];
        @endphp

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3" x-reveal>
            @foreach($vTypes as $vt)
            @php
                $verification = $verifications->firstWhere('type', $vt['key']);
                $status = $verification?->status ?? 'unsubmitted';
                $chip = [
                    'approved' => ['os-chip-grass', 'bi-check-circle-fill', 'Approved'],
                    'pending' => ['os-chip-mango', 'bi-clock-fill', 'Pending review'],
                    'rejected' => ['os-chip-ember', 'bi-x-circle-fill', 'Rejected'],
                    'unsubmitted' => ['os-chip-slate', 'bi-dash-circle-fill', 'Not submitted'],
                ][$status] ?? ['os-chip-slate', 'bi-dash-circle-fill', 'Not submitted'];
            @endphp
            <div class="os-card os-card-hover relative flex flex-col p-6 text-center {{ $status === 'approved' ? 'ring-1 ring-grass/50' : ($status === 'pending' ? 'ring-1 ring-mango/50' : ($status === 'rejected' ? 'ring-1 ring-ember/40' : '')) }}">
                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl text-xl {{ $status === 'approved' ? 'bg-grass/10 text-grass-deep' : ($status === 'pending' ? 'bg-mango/15 text-mango-ink' : ($status === 'rejected' ? 'bg-ember/10 text-ember-deep' : 'bg-paper-deep text-slate')) }}">
                    <i class="bi {{ $vt['icon'] }}"></i>
                </span>
                <h2 class="mt-3 text-sm font-bold text-ink">{{ $vt['label'] }}</h2>
                <span class="os-chip mx-auto mt-2 {{ $chip[0] }}"><i class="bi {{ $chip[1] }}"></i> {{ $chip[2] }}</span>

                @if($verification?->rejection_reason)
                <div class="mt-3 rounded-lg border border-ember/25 bg-ember/5 p-2.5 text-left text-[11px] leading-relaxed text-ember-deep">
                    <strong>Reason:</strong> {{ $verification->rejection_reason }}
                </div>
                @endif

                @if(in_array($status, ['unsubmitted', 'rejected']) && !in_array($vt['key'], ['email', 'store_terms']))
                <form action="{{ route('profile.verification.submit') }}" method="POST" class="mt-4 flex flex-col gap-2 text-left" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $vt['key'] }}">
                    <input type="text" name="document_number" class="os-input os-input-sm" placeholder="Document number (optional)" aria-label="Document number (optional) for {{ $vt['label'] }}">
                    <input type="file" name="document" class="os-input os-input-sm py-1.5 text-xs" required aria-label="Upload document for {{ $vt['label'] }}">
                    <button type="submit" class="os-btn os-btn-brand os-btn-sm mt-1">{{ $status === 'rejected' ? 'Resubmit' : 'Upload & submit' }}</button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
