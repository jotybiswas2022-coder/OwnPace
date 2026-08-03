@extends('backend.layouts.console')
@section('title', 'Roles & Permissions — '.storeName().' Admin')
@section('page_title', 'Roles & Permissions')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Roles & Permissions']]])
@endsection

@section('content')

@if(session('success'))
<div class="mb-4 flex items-start gap-2 rounded-xl border border-grass/25 bg-grass/10 p-4 text-sm text-grass">
    <i class="bi bi-check-circle-fill mt-0.5"></i> {{ session('success') }}
</div>
@endif
@if(session('error') || $errors->any())
<div class="mb-4 flex items-start gap-2 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill mt-0.5"></i> {{ session('error') ?? $errors->first() }}
</div>
@endif

<!-- Create -->
<div class="os-card p-6">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Create a role</h2>
        <p class="mt-0.5 text-sm text-slate">New roles start with no permissions — open the role afterwards to assign them.</p>
    </div>
    <form action="{{ route('admin.roles.store') }}" method="POST" class="mt-4 flex flex-wrap items-end gap-2">
        @csrf
        <div class="w-72">
            <label class="mb-1.5 block text-xs font-semibold text-slate">Role name <span class="text-ember">*</span></label>
            <input type="text" name="name" class="os-input w-full" placeholder="e.g. Support Agent" required>
        </div>
        <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-plus-lg"></i> Create role</button>
    </form>
</div>

<!-- List -->
<div class="os-card mt-6 overflow-hidden">
    <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
        <h3 class="font-display text-sm font-bold text-ink">All roles</h3>
        <span class="text-xs text-slate">{{ $roles->count() }} roles · {{ count($allPermissions) }} permissions</span>
    </div>
    <div class="divide-y divide-ink/5">
        @forelse($roles as $role)
        <div class="flex flex-wrap items-center gap-3 px-5 py-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $role->name === 'Super Admin' ? 'bg-mango/15 text-mango-deep' : 'bg-brand/10 text-brand' }}">
                <i class="bi bi-person-badge-fill"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-ink">{{ $role->name }}</p>
                <p class="text-xs text-slate">{{ $role->users_count }} user(s) · {{ $role->permissions->count() }} permission(s)</p>
            </div>
            @if($role->name !== 'Super Admin')
            <a href="{{ route('admin.roles.edit', $role) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-pencil-fill"></i> Edit</a>
            <form action="{{ route('admin.roles.delete', $role) }}" method="POST" onsubmit="return confirm('Delete role "{{ addslashes($role->name) }}"?')">
                @csrf
                <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-trash-fill"></i></button>
            </form>
            @else
            <span class="os-chip os-chip-mango"><i class="bi bi-shield-lock-fill"></i> Built-in</span>
            @endif
        </div>
        @empty
        <p class="px-5 py-10 text-center text-sm text-slate">No roles yet.</p>
        @endforelse
    </div>
</div>

@endsection
