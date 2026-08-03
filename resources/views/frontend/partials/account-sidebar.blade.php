{{-- Shared account-area sidebar — included by every customer account page so
     navigation stays consistent as sections grow. Self-contained styles. --}}
@push('styles')
<style>
.acc-sidebar {
    background: var(--card-dark);
    border: 1px solid var(--card-border);
    border-radius: var(--radius);
    padding: 24px;
    position: sticky; top: 100px;
    transition: all 0.3s ease;
}
.acc-sidebar:hover {
    border-color: rgba(234,179,8,0.15);
    box-shadow: var(--shadow-glow-sm);
}
.acc-avatar { text-align: center; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--card-border); }
.acc-avatar-circle {
    width: 68px; height: 68px; border-radius: 50%;
    background: linear-gradient(135deg, var(--gold-500), var(--gold-600));
    color: var(--near-black); display: flex; align-items: center; justify-content: center;
    font-size: 26px; font-weight: 800; font-family: 'Syne', sans-serif;
    margin: 0 auto 12px; box-shadow: var(--shadow-gold);
    transition: transform 0.3s;
}
.acc-sidebar:hover .acc-avatar-circle { transform: scale(1.05); }
.acc-avatar h5 { color: var(--text-primary); font-size: 16px; font-weight: 600; margin: 0; word-break: break-word; }
.acc-avatar-email { color: var(--text-dim); font-size: 12px; word-break: break-all; }
.acc-nav { display: flex; flex-direction: column; gap: 4px; }
.acc-nav a {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 8px;
    color: var(--text-muted); font-size: 13px; font-weight: 500;
    transition: all 0.3s; text-decoration: none;
    border-left: 3px solid transparent;
    touch-action: manipulation;
}
.acc-nav a:hover { background: rgba(234,179,8,0.08); color: var(--gold-400); }
.acc-nav a.active {
    background: rgba(234,179,8,0.12);
    color: var(--gold-400); font-weight: 600;
    border-left-color: var(--gold-500);
}
.acc-nav a i { width: 18px; font-size: 14px; text-align: center; }
.acc-logout {
    width: 100%; margin-top: 16px; padding: 11px;
    background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
    color: #ef4444; border-radius: 8px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    transition: all 0.3s; font-family: inherit;
}
.acc-logout:hover { background: rgba(239,68,68,0.15); border-color: #ef4444; }
@media (max-width: 991px) {
    .acc-sidebar { position: static; margin-bottom: 24px; }
}
</style>
@endpush

<aside class="acc-sidebar reveal-left" aria-label="Account navigation">
    <div class="acc-avatar">
        <div class="acc-avatar-circle">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
        <h5>{{ auth()->user()->name ?? 'User' }}</h5>
        <span class="acc-avatar-email">{{ auth()->user()->email }}</span>
    </div>
    <nav class="acc-nav">
        <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.index') ? 'active' : '' }}"><i class="bi bi-person-fill"></i> Profile</a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="bi bi-gear-fill"></i> Settings</a>
        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'active' : '' }}"><i class="bi bi-box-seam-fill"></i> My Orders</a>
        <a href="{{ route('requests.index') }}" class="{{ request()->routeIs('requests.*') ? 'active' : '' }}"><i class="bi bi-inboxes-fill"></i> My Requests</a>
        <a href="{{ route('requests.product.create') }}" class="{{ request()->routeIs('requests.product.create') ? 'active' : '' }}"><i class="bi bi-plus-square-fill"></i> Request a Product</a>
        <a href="{{ route('wallet.index') }}" class="{{ request()->routeIs('wallet.*') ? 'active' : '' }}"><i class="bi bi-wallet2"></i> Wallet</a>
        <a href="{{ route('wishlist.index') }}" class="{{ request()->routeIs('wishlist.*') ? 'active' : '' }}"><i class="bi bi-heart-fill"></i> Wishlist</a>
        <a href="{{ route('profile.addresses') }}" class="{{ request()->routeIs('profile.addresses*') ? 'active' : '' }}"><i class="bi bi-geo-alt-fill"></i> Addresses</a>
        <a href="{{ route('profile.cards') }}" class="{{ request()->routeIs('profile.cards*') ? 'active' : '' }}"><i class="bi bi-credit-card-fill"></i> Cards</a>
        <a href="{{ route('profile.banks') }}" class="{{ request()->routeIs('profile.banks*') ? 'active' : '' }}"><i class="bi bi-bank"></i> Bank Accounts</a>
        <a href="{{ route('profile.verification') }}" class="{{ request()->routeIs('profile.verification*') ? 'active' : '' }}"><i class="bi bi-patch-check-fill"></i> Verification</a>
    </nav>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="acc-logout" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button>
    </form>
</aside>
