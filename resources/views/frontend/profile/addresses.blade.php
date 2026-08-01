@extends('frontend.app')
@section('title', 'My Addresses — OwnPace Store')

@push('styles')
<style>
/* ===== ADDRESSES HERO ===== */
.fp-ad-hero {
    position: relative; padding: 50px 0 28px; overflow: hidden;
    background: linear-gradient(135deg, #0A0A0B 0%, #1A1A1E 30%, #0A0A0B 70%);
    border-bottom: 1px solid var(--card-border);
}
.fp-ad-hero-grid {
    position: absolute; inset: 0; pointer-events: none;
    background-image:
        linear-gradient(rgba(234,179,8,0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(234,179,8,0.025) 1px, transparent 1px);
    background-size: 60px 60px;
}
.fp-ad-orb {
    position: absolute; width: 400px; height: 400px; border-radius: 50%;
    background: radial-gradient(circle, rgba(234,179,8,0.04) 0%, transparent 60%);
    top: -150px; right: -80px; pointer-events: none;
    animation: adPulse 6s ease-in-out infinite;
}
@keyframes adPulse { 0%,100%{transform:scale(1);opacity:0.4} 50%{transform:scale(1.15);opacity:0.8} }

.fp-ad-section { padding-bottom: 80px; min-height: 60vh; }
.fp-alert { display:flex;align-items:center;gap:10px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);color:#4ade80;padding:14px 20px;border-radius:var(--radius-sm);font-weight:500;font-size:13px;margin-bottom:24px;animation:alertSlide 0.4s ease-out; }
@keyframes alertSlide { from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

.fp-address-card {
    background: var(--card-dark); border: 1px solid var(--card-border);
    border-radius: var(--radius); padding: 24px;
    position: relative; height: 100%;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fp-address-card::after {
    content: ''; position: absolute; inset: 0; border-radius: var(--radius);
    pointer-events: none; opacity: 0;
    transition: opacity 0.4s;
    box-shadow: inset 0 0 0 1px rgba(234,179,8,0.15);
}
.fp-address-card:hover::after { opacity: 1; }
.fp-address-card.default { border-color: rgba(234,179,8,0.35); }
.fp-address-card:hover {
    border-color: rgba(234,179,8,0.25);
    transform: translateY(-4px);
    box-shadow: 0 12px 36px rgba(0,0,0,0.3);
}
.fp-addr-default-badge {
    position: absolute; top: 12px; right: 12px; z-index: 1;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); padding: 4px 10px;
    border-radius: 6px; font-size: 10px; font-weight: 700;
    display: flex; align-items: center; gap: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.fp-addr-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: rgba(234,179,8,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-500); font-size: 20px; margin-bottom: 12px;
}
.fp-address-card h5 { color: var(--text-primary); font-size: 16px; font-weight: 600; margin-bottom: 6px; }
.fp-address-card p { color: var(--text-muted); font-size: 13px; line-height: 1.6; margin-bottom: 4px; }
.fp-addr-phone { color: var(--text-dim); font-size: 12px; }
.fp-addr-actions { margin-top: 14px; display: flex; gap: 6px; }
.fp-addr-btn {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    border: 1px solid var(--card-border); color: var(--text-dim);
    font-size: 14px; transition: all 0.3s; text-decoration: none;
}
.fp-addr-btn.edit:hover { border-color: var(--gold-400); color: var(--gold-400); background: rgba(234,179,8,0.04); }
.fp-addr-btn.delete:hover { border-color: rgba(239,68,68,0.3); color: #ef4444; background: rgba(239,68,68,0.04); }

.fp-input { width:100%;padding:12px 16px;background:var(--surface-dark);border:1.5px solid var(--card-border);border-radius:var(--radius-sm);color:var(--text-primary);font-size:14px;font-family:inherit;outline:none;transition:all 0.25s ease; }
.fp-input:focus { border-color:var(--gold-500);box-shadow:0 0 0 3px rgba(234,179,8,0.08); }
.fp-input::placeholder { color:var(--text-dim); }

.fp-addr-empty {
    text-align: center; padding: 60px 20px;
}
.fp-addr-empty-icon {
    width: 72px; height: 72px; border-radius: 20px;
    background: var(--card-dark); border: 1px solid var(--card-border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px; font-size: 28px; color: var(--text-dim);
    transition: all 0.3s;
}
.fp-addr-empty:hover .fp-addr-empty-icon {
    border-color: rgba(234,179,8,0.2); transform: scale(1.05);
}
.fp-addr-empty p { color: var(--text-muted); font-size: 15px; margin: 0; }

.fp-modal .modal-content { background:var(--card-dark);border:1px solid var(--card-border);border-radius:var(--radius-lg); }
.fp-modal .modal-header { border-bottom-color:var(--card-border);padding:20px 24px; }
.fp-modal .modal-title { color:var(--text-primary);font-family:'Syne',sans-serif;font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px; }
.fp-modal .modal-title i { color:var(--gold-500); }
.fp-modal .modal-body { padding:24px; }
.fp-modal .modal-footer { border-top-color:var(--card-border);padding:16px 24px; }

@media (max-width: 768px) {
    .fp-ad-hero { padding: 36px 0 20px; }
}
</style>
@endpush

@section('content')
<section class="fp-ad-hero">
    <div class="fp-ad-hero-grid" aria-hidden="true"></div>
    <div class="fp-ad-orb" aria-hidden="true"></div>
    <div class="container">
        <div class="section-head reveal-up" style="text-align:left;">
            <div class="section-badge" style="display:inline-flex;"><i class="bi bi-geo-alt-fill"></i> Delivery Addresses</div>
            <h2>My Addresses</h2>
            <p>Manage your saved delivery addresses</p>
        </div>
    </div>
</section>

<section class="fp-ad-section">
    <div class="container">
        @if(session('success'))
        <div class="fp-alert reveal-up"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-end mb-4 reveal-up">
            <a href="#" class="btn-primary-gold" data-bs-toggle="modal" data-bs-target="#addAddressModal"><i class="bi bi-plus-lg"></i> Add Address</a>
        </div>

        <div class="row g-4">
            @forelse($addresses ?? [] as $address)
            <div class="col-lg-4 col-md-6">
                <div class="fp-address-card {{ $address->is_default ? 'default' : '' }} reveal-up">
                    @if($address->is_default)<span class="fp-addr-default-badge"><i class="bi bi-pin-fill"></i> Default</span>@endif
                    <div class="fp-addr-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <h5>{{ $address->label ?? 'Address' }}</h5>
                    <p>{{ $address->address_line1 }}{{ $address->address_line2 ? ', ' . $address->address_line2 : '' }}, {{ $address->city }}, {{ $address->state }}</p>
                    <span class="fp-addr-phone"><i class="bi bi-telephone-fill"></i> {{ $address->phone }}</span>
                    <div class="fp-addr-actions">
                        <a href="#" class="fp-addr-btn edit" data-bs-toggle="modal" data-bs-target="#editAddress{{ $address->id }}"><i class="bi bi-pencil-fill"></i></a>
                        <a href="{{ route('profile.addresses.delete', $address) }}" class="fp-addr-btn delete" onclick="return confirm('Delete this address?')"><i class="bi bi-trash-fill"></i></a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="fp-addr-empty reveal-up">
                    <div class="fp-addr-empty-icon"><i class="bi bi-geo-alt"></i></div>
                    <p>No addresses saved yet. Add a delivery address!</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fp-modal">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-geo-alt-fill"></i> Add New Address</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.addresses.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6"><input type="text" name="recipient_name" class="fp-input" placeholder="Recipient name" required></div>
                        <div class="col-6"><input type="text" name="label" class="fp-input" placeholder="Label (e.g. Home, Office)"></div>
                        <div class="col-12"><input type="text" name="address_line1" class="fp-input" placeholder="Street address" required></div>
                        <div class="col-6"><input type="text" name="city" class="fp-input" placeholder="City" required></div>
                        <div class="col-6"><input type="text" name="state" class="fp-input" placeholder="State" required></div>
                        <div class="col-12"><input type="text" name="phone" class="fp-input" placeholder="Phone number" required></div>
                        <div class="col-12">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <input type="checkbox" name="is_default" value="1" style="width:16px;height:16px;accent-color:var(--gold-500);">
                                <span style="color:var(--text-muted);font-size:13px;">Set as default address</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary-gold w-100 justify-content-center">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== EDIT MODALS ===== --}}
@foreach($addresses ?? [] as $address)
<div class="modal fade" id="editAddress{{ $address->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fp-modal">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-fill"></i> Edit Address</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.addresses.update', $address) }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6"><input type="text" name="recipient_name" class="fp-input" value="{{ $address->recipient_name }}" placeholder="Recipient name" required></div>
                        <div class="col-6"><input type="text" name="label" class="fp-input" value="{{ $address->label }}" placeholder="Label (e.g. Home, Office)"></div>
                        <div class="col-12"><input type="text" name="address_line1" class="fp-input" value="{{ $address->address_line1 }}" placeholder="Street address" required></div>
                        <div class="col-6"><input type="text" name="city" class="fp-input" value="{{ $address->city }}" placeholder="City" required></div>
                        <div class="col-6"><input type="text" name="state" class="fp-input" value="{{ $address->state }}" placeholder="State" required></div>
                        <div class="col-12"><input type="text" name="phone" class="fp-input" value="{{ $address->phone }}" placeholder="Phone number" required></div>
                        <div class="col-12">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <input type="checkbox" name="is_default" value="1" {{ $address->is_default ? 'checked' : '' }} style="width:16px;height:16px;accent-color:var(--gold-500);">
                                <span style="color:var(--text-muted);font-size:13px;">Set as default address</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-primary-gold w-100 justify-content-center">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@include('frontend.partials.footer')
@endsection
