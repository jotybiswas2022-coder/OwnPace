@extends('backend.layouts.console')
@section('title', 'Edit Role — '.($role->name ?? '').' | '.storeName().' Admin')
@section('page_title', 'Edit role: '.($role->name ?? ''))

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', [
        'crumbs' => [
            ['label' => 'Roles & Permissions', 'route' => 'admin.roles.index'],
            ['label' => $role->name ?? 'Role'],
        ],
    ])
@endsection

@section('content')

@if($errors->any())
<div class="mb-4 flex items-start gap-2 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill mt-0.5"></i> {{ $errors->first() }}
</div>
@endif

<form action="{{ route('admin.roles.update', $role) }}" method="POST">
    @csrf

    <div class="os-card p-6">
        <label class="mb-1.5 block text-xs font-semibold text-slate">Role name <span class="text-ember">*</span></label>
        <input type="text" name="name" value="{{ $role->name }}" class="os-input w-full max-w-md" required>
        <p class="mt-1.5 text-xs text-slate">Grant permissions below, then save. Customers always start with zero permissions.</p>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($groups as $module => $perms)
        <div class="os-card p-5">
            <h3 class="font-display text-sm font-bold text-ink">{{ $module }}</h3>
            <div class="mt-3 space-y-2.5">
                @foreach($perms as $perm)
                <label class="flex cursor-pointer items-center gap-2.5 rounded-lg border border-ink/10 px-3 py-2.5 transition-colors hover:border-brand/40 {{ in_array($perm, $rolePermissions) ? 'border-brand/40 bg-brand/5' : '' }}">
                    <input type="checkbox" name="permissions[]" value="{{ $perm }}" class="h-4 w-4 rounded accent-brand"
                        {{ in_array($perm, $rolePermissions) ? 'checked' : '' }}>
                    <span class="text-sm text-ink">{{ $perm }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6 flex items-center gap-2">
        <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Save role</button>
        <a href="{{ route('admin.roles.index') }}" class="os-btn os-btn-ghost">Cancel</a>
    </div>
</form>

@endsection
