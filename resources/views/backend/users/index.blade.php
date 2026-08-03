@extends('backend.layouts.console')
@section('title', 'Customers — '.storeName().' Admin')
@section('page_title', 'Customers')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Customers']]])
@endsection

@section('content')

<div class="os-card p-5">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <label class="mb-1.5 block text-xs font-semibold text-slate">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="os-input w-full" placeholder="Name, email or phone…">
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Role</label>
            <select name="role" class="os-input w-full">
                <option value="">All roles</option>
                @if($roles->isNotEmpty())
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                @else
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin (legacy)</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Customer</option>
                @endif
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Status</label>
            <select name="status" class="os-input w-full">
                <option value="">All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-semibold text-slate">Identity check</label>
            <select name="verification" class="os-input w-full">
                <option value="">All</option>
                @foreach(['verified', 'pending', 'rejected', 'unverified'] as $v)
                    <option value="{{ $v }}" {{ request('verification') === $v ? 'selected' : '' }}>{{ ucfirst($v) }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-5">
            <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-funnel-fill"></i> Filter</button>
            @if(request()->has('search') || request()->has('role') || request()->has('status') || request()->has('verification'))
                <a href="{{ route('admin.users.index') }}" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-x-lg"></i> Clear</a>
            @endif
        </div>
    </form>
</div>

<div class="os-card mt-6 overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <h3 class="font-display text-sm font-bold text-ink">All customers</h3>
        <span class="text-xs text-slate">{{ $users->total() }} total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Orders</th>
                    <th>Identity</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th class="w-48">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand/10 font-display text-sm font-bold text-brand">{{ strtoupper(substr($u->name ?? '?', 0, 1)) }}</span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-ink">{{ $u->name ?? 'N/A' }}</p>
                                <p class="truncate text-xs text-slate">{{ $u->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm text-slate">{{ $u->phone ?? '—' }}</td>
                    <td class="font-mono text-sm text-ink">{{ $u->orders_count }}</td>
                    <td>
                        @php
                            $iv = $u->identity_verification ?? 'unverified';
                            $vchip = $iv === 'verified' ? 'grass' : ($iv === 'rejected' ? 'ember' : ($iv === 'pending' ? 'mango' : 'brand'));
                        @endphp
                        <span class="os-chip os-chip-{{ $vchip }}">{{ ucfirst($iv) }}</span>
                    </td>
                    <td>
                        @if($u->is_suspended)
                            <span class="os-chip os-chip-ember"><i class="bi bi-lock-fill"></i> Suspended</span>
                        @elseif(!$u->is_active)
                            <span class="os-chip os-chip-brand">Inactive</span>
                        @else
                            <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> Active</span>
                        @endif
                    </td>
                    <td>
                        @if($u->getRoleNames()->isNotEmpty())
                            @foreach($u->getRoleNames() as $rn)
                                <span class="os-chip {{ $rn === 'Super Admin' ? 'os-chip-mango' : 'os-chip-brand' }}">{{ $rn }}</span>
                            @endforeach
                        @else
                            <span class="text-xs text-slate">{{ $u->is_admin ? 'Admin (legacy)' : 'Customer' }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.users.show', $u) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-eye-fill"></i> View</a>
                            @if($u->is_suspended)
                                <form action="{{ route('admin.users.unsuspend', $u) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-grass" title="Unsuspend"><i class="bi bi-unlock-fill"></i></button>
                                </form>
                            @else
                                <form action="{{ route('admin.users.suspend', $u) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-mango-deep" title="Suspend"><i class="bi bi-lock-fill"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-sm text-slate">No customers match your filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-ink/10 px-5 py-4">
        {{ $users->links() }}
    </div>
</div>

@endsection
