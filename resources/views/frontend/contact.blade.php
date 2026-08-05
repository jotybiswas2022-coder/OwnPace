@extends('frontend.layouts.store')
@section('title', 'Contact Us — '.storeName())

@php
    $osSettings = App\Models\Setting::first();
    $osEmail = $osSettings?->email ?? 'support@'.strtolower(str_replace(' ', '', storeName())).'.com';
    $osPhone = $osSettings?->phone ?? '+234 800 000 0000';
    $osLocation = $osSettings?->location ?? 'Lagos, Nigeria';
@endphp

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-headset"></i> Contact Us</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">We're here to help</h1>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate sm:text-base">Questions about payments, delivery or your account? Reach out — we get back to you within 24 hours.</p>
    </div>
</section>

<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <div class="grid gap-10 lg:grid-cols-5">
            {{-- Contact info --}}
            <div class="space-y-4 lg:col-span-2" x-reveal>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="os-card os-card-hover p-5">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-envelope-fill"></i></span>
                        <p class="mt-3 text-xs font-bold uppercase tracking-wider text-slate">Email</p>
                        <p class="mt-1 break-all text-sm font-semibold text-ink">{{ $osEmail }}</p>
                    </div>
                    <div class="os-card os-card-hover p-5">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-telephone-fill"></i></span>
                        <p class="mt-3 text-xs font-bold uppercase tracking-wider text-slate">Phone</p>
                        <p class="mt-1 text-sm font-semibold text-ink">{{ $osPhone }}</p>
                    </div>
                    <div class="os-card os-card-hover p-5">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-geo-alt-fill"></i></span>
                        <p class="mt-3 text-xs font-bold uppercase tracking-wider text-slate">Location</p>
                        <p class="mt-1 text-sm font-semibold text-ink">{{ $osLocation }}</p>
                    </div>
                    <div class="os-card os-card-hover p-5">
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-clock-fill"></i></span>
                        <p class="mt-3 text-xs font-bold uppercase tracking-wider text-slate">Working Hours</p>
                        <p class="mt-1 text-sm font-semibold text-ink">Mon–Sat: 8AM – 6PM (WAT)</p>
                    </div>
                </div>

                <div class="os-card p-5">
                    <p class="text-sm font-semibold text-ink">Prefer a quick answer?</p>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        <a href="{{ url('/faq') }}" class="os-btn os-btn-ghost os-btn-sm justify-start"><i class="bi bi-question-circle-fill"></i> Browse FAQs</a>
                        <a href="{{ url('/about') }}" class="os-btn os-btn-ghost os-btn-sm justify-start"><i class="bi bi-info-circle-fill"></i> About Us</a>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="lg:col-span-3" x-reveal="120">
                @auth
                <div class="os-card p-6 sm:p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo/10 text-xl text-brand"><i class="bi bi-chat-dots-fill"></i></span>
                        <div>
                            <h2 class="font-display text-lg font-bold text-ink">Send us a message</h2>
                            <p class="text-sm text-slate">Fill in the form and we'll respond promptly</p>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="mb-5 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
                            <p class="flex items-center gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the errors below before sending.</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}" class="grid gap-5 sm:grid-cols-2">
                        @csrf
                        <div>
                            <label for="contact-name" class="os-label">Full Name</label>
                            <input id="contact-name" type="text" name="name" class="os-input {{ $errors->has('name') ? 'os-input-error' : '' }}" placeholder="John Doe" required value="{{ old('name', auth()->user()->name) }}">
                            @error('name') <p class="os-error-text">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="contact-email" class="os-label">Email</label>
                            <input id="contact-email" type="email" name="email" class="os-input {{ $errors->has('email') ? 'os-input-error' : '' }}" placeholder="john@example.com" required value="{{ old('email', auth()->user()->email) }}">
                            @error('email') <p class="os-error-text">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="contact-subject" class="os-label">Subject</label>
                            <select id="contact-subject" name="subject" class="os-input" required>
                                <option value="" disabled selected>Select a topic…</option>
                                @foreach(['Payment' => 'Payment Issue', 'Delivery' => 'Delivery Question', 'Installment' => 'Installment Plan', 'Account' => 'Account Support', 'Product' => 'Product Inquiry', 'Other' => 'Other'] as $osValue => $osLabel)
                                    <option value="{{ $osValue }}" {{ old('subject') === $osValue ? 'selected' : '' }}>{{ $osLabel }}</option>
                                @endforeach
                            </select>
                            @error('subject') <p class="os-error-text">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="contact-message" class="os-label">Message</label>
                            <textarea id="contact-message" name="message" rows="5" class="os-input {{ $errors->has('message') ? 'os-input-error' : '' }}" placeholder="Describe your issue or question…" required>{{ old('message') }}</textarea>
                            @error('message') <p class="os-error-text">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2 flex flex-wrap items-center gap-4">
                            <button type="submit" class="os-btn os-btn-mango"><i class="bi bi-send-fill"></i> Send Message</button>
                            <p class="text-xs text-slate"><i class="bi bi-shield-lock"></i> Your details stay private — we only use them to respond.</p>
                        </div>
                    </form>
                </div>
                @else
                <div class="os-card flex flex-col items-center p-10 text-center">
                    <span class="os-empty-icon"><i class="bi bi-person-lock"></i></span>
                    <h2 class="mt-5 font-display text-lg font-bold text-ink">Log in to send a message</h2>
                    <p class="mt-2 max-w-sm text-sm text-slate">Sign in so our team can match your message to your account and follow up faster.</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <a href="{{ url('/login') }}" class="os-btn os-btn-brand"><i class="bi bi-box-arrow-in-right"></i> Log in</a>
                        <a href="{{ url('/register') }}" class="os-btn os-btn-ghost"><i class="bi bi-person-plus"></i> Create account</a>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</section>

@endsection
