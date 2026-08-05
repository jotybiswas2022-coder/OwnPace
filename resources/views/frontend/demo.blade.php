@extends('frontend.layouts.store')
@section('title', 'Design System — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <span class="os-eyebrow"><i class="bi bi-palette-fill"></i> Design System</span>
        <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">{{ storeName() }} <span class="text-brand">Living Style Guide</span></h1>
        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate sm:text-base">Every component, token and interaction powering the storefront and admin console — all in one place.</p>
    </div>
</section>

{{-- ===== COLORS ===== --}}
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 class="font-display text-2xl font-bold text-ink" x-reveal>Color tokens</h2>
        <p class="mt-1 text-sm text-slate">Brand, support and neutral colors — all AA-checked against their text pairings.</p>
        <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6" x-reveal="100">
            @php
                $colors = [
                    ['brand', 'bg-brand', 'text-white', 'Indigo', 'Brand / primary'],
                    ['mango', 'bg-mango', 'text-ink', 'Mango', 'CTA / accent'],
                    ['grass', 'bg-grass', 'text-white', 'Grass', 'Success'],
                    ['ember', 'bg-ember', 'text-white', 'Ember', 'Danger'],
                    ['ink', 'bg-ink', 'text-white', 'Ink', 'Text'],
                    ['paper', 'bg-paper', 'text-ink border border-ink/10', 'Paper', 'Background'],
                ];
            @endphp
            @foreach($colors as [$name, $bg, $fg, $label, $role])
            <div class="overflow-hidden rounded-2xl border border-ink/10">
                <div class="flex h-20 items-end p-3 {{ $bg }} {{ $fg }}"><span class="text-xs font-bold">{{ $label }}</span></div>
                <div class="bg-white p-3">
                    <p class="font-mono text-xs font-semibold text-ink">--{{ $name }}</p>
                    <p class="text-[11px] text-slate">{{ $role }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== BUTTONS & CHIPS ===== --}}
<section class="os-section border-t border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 class="font-display text-2xl font-bold text-ink" x-reveal>Buttons & chips</h2>
        <div class="mt-6 flex flex-wrap items-center gap-3" x-reveal="100">
            <button type="button" class="os-btn os-btn-brand">Primary brand</button>
            <button type="button" class="os-btn os-btn-mango">Primary mango</button>
            <button type="button" class="os-btn os-btn-ghost">Ghost</button>
            <button type="button" class="os-btn os-btn-danger"><i class="bi bi-trash"></i> Danger</button>
            <button type="button" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-plus-lg"></i> Small</button>
        </div>
        <div class="mt-5 flex flex-wrap items-center gap-2" x-reveal="150">
            <span class="os-chip os-chip-brand">Brand</span>
            <span class="os-chip os-chip-mango">Mango</span>
            <span class="os-chip os-chip-grass">Grass</span>
            <span class="os-chip os-chip-ember">Ember</span>
            <span class="os-chip os-chip-slate">Slate</span>
        </div>
    </div>
</section>

{{-- ===== CARDS & STATS ===== --}}
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 class="font-display text-2xl font-bold text-ink" x-reveal>Cards & statistics</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-3" x-reveal="100">
            <div class="os-card os-card-hover p-6">
                <p class="os-stat-label"><i class="bi bi-wallet2"></i> Wallet balance</p>
                <p class="mt-2 font-mono text-2xl font-bold text-ink">₦1,250,000</p>
                <p class="mt-1 text-xs text-slate">Spendable balance</p>
            </div>
            <div class="os-card os-card-hover p-6">
                <p class="os-stat-label"><i class="bi bi-receipt"></i> Active orders</p>
                <p class="mt-2 font-mono text-2xl font-bold text-mango-deep">12</p>
                <p class="mt-1 text-xs text-slate">Across 5 plans</p>
            </div>
            <div class="os-card os-card-hover p-6">
                <p class="os-stat-label"><i class="bi bi-star-fill"></i> Next payment</p>
                <p class="mt-2 font-mono text-2xl font-bold text-grass-deep">Aug 12</p>
                <p class="mt-1 text-xs text-slate">₦45,000 due</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== FORMS ===== --}}
<section class="os-section border-t border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 class="font-display text-2xl font-bold text-ink" x-reveal>Form fields</h2>
        <div class="mt-6 grid gap-6 lg:grid-cols-2" x-reveal="100">
            <div class="os-card p-6">
                <div class="space-y-4">
                    <div>
                        <label for="demo_email" class="os-label">Email address</label>
                        <input type="email" id="demo_email" class="os-input w-full" placeholder="you@example.com">
                    </div>
                    <div>
                        <label for="demo_plan" class="os-label">Payment plan</label>
                        <select id="demo_plan" class="os-input w-full">
                            <option>3 months</option>
                            <option>6 months</option>
                            <option>12 months</option>
                        </select>
                    </div>
                    <div>
                        <label for="demo_msg" class="os-label">Message</label>
                        <textarea id="demo_msg" class="os-input w-full" placeholder="How can we help?"></textarea>
                        <p class="os-help-text">We usually reply within one business day.</p>
                    </div>
                </div>
            </div>
            <div class="os-card p-6">
                <div class="space-y-4">
                    <div>
                        <label for="demo_bad" class="os-label">Invalid field</label>
                        <input type="text" id="demo_bad" class="os-input os-input-error w-full" value="not-an-email">
                        <p class="os-error-text">Please enter a valid email address.</p>
                    </div>
                    <div>
                        <span class="os-label">Toggle</span>
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-slate">
                            <input type="checkbox" checked class="h-4 w-4 rounded accent-brand">
                            Notify me about new products
                        </label>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-paper-deep/60 p-4">
                        <div>
                            <p class="text-sm font-semibold text-ink">Toggle example</p>
                            <p class="text-xs text-slate">Alpine-powered switches</p>
                        </div>
                        <div x-data="{ on: true }">
                            <button type="button" role="switch" :aria-checked="on" @click="on = !on" class="flex h-7 w-12 items-center rounded-full p-1 transition-colors" :class="on ? 'bg-grass' : 'bg-ink/15'">
                                <span class="h-5 w-5 rounded-full bg-white shadow transition-transform" :class="on ? 'translate-x-5' : ''"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== TABLE (mobile-collapsible) ===== --}}
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 class="font-display text-2xl font-bold text-ink" x-reveal>Tables — collapse to cards on mobile</h2>
        <div class="mt-6 overflow-hidden rounded-2xl border border-ink/10 bg-white" x-reveal="100">
            <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
                <h3 class="font-display text-sm font-bold text-ink">Recent orders</h3>
                <span class="os-chip os-chip-mango">3 shown</span>
            </div>
            <div class="overflow-x-auto">
                <table class="os-table w-full">
                    <thead>
                        <tr><th>Order</th><th>Product</th><th>Plan</th><th>Status</th><th>Amount</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Order" class="font-mono text-sm text-ink">#1042</td>
                            <td data-label="Product" class="font-semibold text-ink">Samsung Galaxy S24</td>
                            <td data-label="Plan"><span class="os-chip">6 months</span></td>
                            <td data-label="Status"><span class="os-chip os-chip-grass">On track</span></td>
                            <td data-label="Amount" class="font-mono text-mango-ink">₦892,000</td>
                        </tr>
                        <tr>
                            <td data-label="Order" class="font-mono text-sm text-ink">#1038</td>
                            <td data-label="Product" class="font-semibold text-ink">HP Pavilion 15</td>
                            <td data-label="Plan"><span class="os-chip">12 months</span></td>
                            <td data-label="Status"><span class="os-chip os-chip-mango">Late</span></td>
                            <td data-label="Amount" class="font-mono text-mango-ink">₦1,150,000</td>
                        </tr>
                        <tr>
                            <td data-label="Order" class="font-mono text-sm text-ink">#1031</td>
                            <td data-label="Product" class="font-semibold text-ink">iPhone 15 Pro</td>
                            <td data-label="Plan"><span class="os-chip">3 months</span></td>
                            <td data-label="Status"><span class="os-chip os-chip-grass">Paid off</span></td>
                            <td data-label="Amount" class="font-mono text-mango-ink">₦1,475,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

{{-- ===== LOADING & EMPTY STATES ===== --}}
<section class="os-section border-t border-ink/10 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 class="font-display text-2xl font-bold text-ink" x-reveal>Loading & empty states</h2>
        <div class="mt-6 grid gap-6 lg:grid-cols-2" x-reveal="100">
            <div class="os-card p-6">
                <p class="os-label mb-3">Skeleton loading</p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="os-skeleton h-12 w-12 flex-shrink-0 os-skeleton-circle"></div>
                        <div class="flex-1 space-y-2">
                            <div class="os-skeleton h-3 w-2/3"></div>
                            <div class="os-skeleton h-3 w-1/3"></div>
                        </div>
                    </div>
                    <div class="os-skeleton h-24 w-full os-skeleton-card"></div>
                </div>
            </div>
            <div class="os-card p-6">
                <p class="os-label mb-3">Empty state with CTA</p>
                <div class="rounded-2xl border border-dashed border-ink/15 py-10 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-mango/15 text-2xl text-mango-deep"><i class="bi bi-bag-x"></i></div>
                    <p class="mt-4 font-semibold text-ink">No orders yet</p>
                    <p class="mx-auto mt-1 max-w-xs text-sm text-slate">Browse the catalog and pick something you'll love — pay over time.</p>
                    <a href="{{ route('shop') }}" class="os-btn os-btn-brand os-btn-sm mt-4"><i class="bi bi-grid-fill"></i> Browse products</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== INTERACTIONS ===== --}}
<section class="os-section">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <h2 class="font-display text-2xl font-bold text-ink" x-reveal>Micro-interactions</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-3" x-reveal="100">
            <div class="os-card os-card-hover p-6">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo/10 text-lg text-brand"><i class="bi bi-arrow-repeat"></i></span>
                <h3 class="mt-3 font-display text-base font-bold text-ink">Scroll reveal</h3>
                <p class="mt-1 text-sm text-slate">Elements fade and rise as they enter the viewport — disabled for reduced motion.</p>
            </div>
            <div class="os-card os-card-hover p-6">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-upc-scan"></i></span>
                <h3 class="mt-3 font-display text-base font-bold text-ink">Count up</h3>
                <p class="mt-1 text-sm text-slate">Animated numbers, like <span class="font-mono font-semibold text-mango-ink" x-countup="15000">0</span> happy customers.</p>
            </div>
            <div class="os-card os-card-hover p-6">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-grass/10 text-lg text-grass-deep"><i class="bi bi-bell-fill"></i></span>
                <h3 class="mt-3 font-display text-base font-bold text-ink">Toast notifications</h3>
                <p class="mt-1 text-sm text-slate">Lightweight, auto-dismissing feedback for every action.</p>
                <button type="button" class="os-btn os-btn-ghost os-btn-sm mt-3" onclick="window.flash?.('Hello from the design system!', 'success')"><i class="bi bi-bell"></i> Try a toast</button>
            </div>
        </div>
    </div>
</section>

@endsection
