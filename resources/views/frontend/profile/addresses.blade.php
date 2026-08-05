@extends('frontend.layouts.store')
@section('title', 'My Addresses — '.storeName())

@section('content')

<section class="os-section-sm border-b border-ink/10 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-end justify-between gap-4 px-4 sm:px-6">
        <div>
            <span class="os-eyebrow"><i class="bi bi-geo-alt-fill"></i> Delivery addresses</span>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl">My addresses</h1>
            <p class="mt-2 text-sm text-slate">Manage your saved delivery addresses.</p>
        </div>
        <button type="button" class="os-btn os-btn-brand os-btn-sm" @click="$dispatch('open-address-modal')"><i class="bi bi-plus-lg"></i> Add address</button>
    </div>
</section>

@include('frontend.partials.account-nav')

<section class="os-section" x-data="{ modal: null }" @open-address-modal.window="modal = 'add'">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        @if($errors->any())
        <div class="mb-6 rounded-xl border border-ember/30 bg-ember/5 p-4" role="alert">
            <p class="flex items-center gap-2 text-sm font-semibold text-ember-deep"><i class="bi bi-exclamation-triangle-fill"></i> Please fix the errors below.</p>
        </div>
        @endif

        @if(($addresses ?? collect())->count() > 0)
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3" x-reveal>
            @foreach($addresses ?? [] as $address)
            <div class="os-card os-card-hover relative p-6 {{ $address->is_default ? 'ring-1 ring-mango/60' : '' }}">
                @if($address->is_default)
                <span class="os-chip os-chip-mango absolute right-4 top-4 px-2.5 py-1 text-[10px]"><i class="bi bi-pin-fill"></i> Default</span>
                @endif
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-mango/15 text-lg text-mango-deep"><i class="bi bi-geo-alt-fill"></i></span>
                <h2 class="mt-3 text-sm font-bold text-ink">{{ $address->label ?? 'Address' }}</h2>
                <p class="mt-1 text-sm leading-relaxed text-slate">{{ $address->address_line1 }}{{ $address->address_line2 ? ', '.$address->address_line2 : '' }}, {{ $address->city }}, {{ $address->state }}</p>
                <p class="mt-1 text-xs text-slate"><i class="bi bi-telephone-fill"></i> {{ $address->phone }}</p>
                <div class="mt-4 flex gap-2 border-t border-ink/5 pt-4">
                    <button type="button" class="os-btn os-btn-ghost os-btn-sm flex-1" @click="modal = {{ $address->id }}"><i class="bi bi-pencil-fill"></i> Edit</button>
                    <a href="{{ route('profile.addresses.delete', $address) }}" class="os-btn os-btn-danger os-btn-sm" onclick="return confirm('Delete this address?')"><i class="bi bi-trash-fill"></i></a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="mx-auto max-w-lg" x-reveal>
            <div class="os-card flex flex-col items-center justify-center px-6 py-16 text-center">
                <span class="os-empty-icon"><i class="bi bi-geo-alt"></i></span>
                <h3 class="mt-5 font-display text-lg font-bold text-ink">No addresses saved yet</h3>
                <p class="mt-2 max-w-sm text-sm leading-relaxed text-slate">Add a delivery address to speed through checkout — it only takes a minute.</p>
                <button type="button" class="os-btn os-btn-brand os-btn-sm mt-6" @click="modal = 'add'"><i class="bi bi-plus-lg"></i> Add your first address</button>
            </div>
        </div>
        @endif

        {{-- ===== ADD / EDIT MODAL ===== --}}
        <div x-cloak x-show="modal !== null" x-transition.opacity class="fixed inset-0 z-[90] flex items-end justify-center bg-ink/60 p-0 backdrop-blur-sm sm:items-center sm:p-6" role="dialog" aria-modal="true" aria-label="Address form" @keydown.escape.window="modal = null">
            <div class="w-full max-w-lg rounded-t-2xl bg-white p-6 shadow-lift sm:rounded-2xl sm:p-7" @click.outside="modal = null">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="font-display text-lg font-bold text-ink"><i class="bi bi-geo-alt-fill mr-2 text-mango-deep"></i> <span x-text="modal === 'add' ? 'Add new address' : 'Edit address'"></span></h3>
                    <button type="button" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate transition-colors hover:bg-paper-deep hover:text-ink" @click="modal = null" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                {{-- Add form --}}
                <form x-show="modal === 'add'" method="POST" action="{{ route('profile.addresses.store') }}" class="grid gap-4 sm:grid-cols-2">
                    @csrf
                    <div>
                        <label for="os-ad-recipient" class="os-label">Recipient name</label>
                        <input id="os-ad-recipient" type="text" name="recipient_name" class="os-input" placeholder="e.g. Ada Obi" required>
                    </div>
                    <div>
                        <label for="os-ad-label" class="os-label">Label</label>
                        <input id="os-ad-label" type="text" name="label" class="os-input" placeholder="Home, Office…">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="os-ad-line1" class="os-label">Street address</label>
                        <input id="os-ad-line1" type="text" name="address_line1" class="os-input" placeholder="12 Marina Road, Phase 2" required>
                    </div>
                    <div>
                        <label for="os-ad-city" class="os-label">City</label>
                        <input id="os-ad-city" type="text" name="city" class="os-input" placeholder="Lagos" required>
                    </div>
                    <div>
                        <label for="os-ad-state" class="os-label">State</label>
                        <input id="os-ad-state" type="text" name="state" class="os-input" placeholder="Lagos" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="os-ad-phone" class="os-label">Phone</label>
                        <input id="os-ad-phone" type="tel" name="phone" class="os-input" placeholder="0801 234 5678" required>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-ink sm:col-span-2">
                        <input type="checkbox" name="is_default" value="1" class="h-4 w-4 accent-mango"> Set as default address
                    </label>
                    <div class="flex items-center justify-end gap-3 sm:col-span-2">
                        <button type="button" class="os-btn os-btn-ghost" @click="modal = null">Cancel</button>
                        <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save address</button>
                    </div>
                </form>

                {{-- Edit form --}}
                @foreach($addresses ?? [] as $address)
                <form x-cloak x-show="modal === {{ $address->id }}" method="POST" action="{{ route('profile.addresses.update', $address) }}" class="grid gap-4 sm:grid-cols-2">
                    @csrf
                    <div>
                        <label for="os-edit-recipient-{{ $address->id }}" class="os-label">Recipient name</label>
                        <input id="os-edit-recipient-{{ $address->id }}" type="text" name="recipient_name" class="os-input" value="{{ $address->recipient_name }}" required>
                    </div>
                    <div>
                        <label for="os-edit-label-{{ $address->id }}" class="os-label">Label</label>
                        <input id="os-edit-label-{{ $address->id }}" type="text" name="label" class="os-input" value="{{ $address->label }}" placeholder="Home, Office…">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="os-edit-line1-{{ $address->id }}" class="os-label">Street address</label>
                        <input id="os-edit-line1-{{ $address->id }}" type="text" name="address_line1" class="os-input" value="{{ $address->address_line1 }}" required>
                    </div>
                    <div>
                        <label for="os-edit-city-{{ $address->id }}" class="os-label">City</label>
                        <input id="os-edit-city-{{ $address->id }}" type="text" name="city" class="os-input" value="{{ $address->city }}" required>
                    </div>
                    <div>
                        <label for="os-edit-state-{{ $address->id }}" class="os-label">State</label>
                        <input id="os-edit-state-{{ $address->id }}" type="text" name="state" class="os-input" value="{{ $address->state }}" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="os-edit-phone-{{ $address->id }}" class="os-label">Phone</label>
                        <input id="os-edit-phone-{{ $address->id }}" type="tel" name="phone" class="os-input" value="{{ $address->phone }}" required>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-ink sm:col-span-2">
                        <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }} class="h-4 w-4 accent-mango"> Set as default address
                    </label>
                    <div class="flex items-center justify-end gap-3 sm:col-span-2">
                        <button type="button" class="os-btn os-btn-ghost" @click="modal = null">Cancel</button>
                        <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save changes</button>
                    </div>
                </form>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection
