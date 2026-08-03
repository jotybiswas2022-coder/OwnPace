@extends('backend.layouts.console')
@section('title', 'Requests — '.storeName().' Admin')
@section('page_title', 'Requests')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Requests']]])
@endsection

@section('content')

@php
    $tabs = [
        'plan-changes' => ['label' => 'Plan Changes', 'count' => $planChanges->where('status', 'pending')->count(), 'icon' => 'bi-arrow-repeat'],
        'exchanges' => ['label' => 'Exchanges', 'count' => $exchanges->where('status', 'pending')->count(), 'icon' => 'bi-arrow-left-right'],
        'product-requests' => ['label' => 'Product Requests', 'count' => $productRequests->where('status', 'pending')->count(), 'icon' => 'bi-plus-circle'],
        'deletions' => ['label' => 'Account Closures', 'count' => $deletions->where('status', 'pending')->count(), 'icon' => 'bi-person-x'],
    ];
@endphp

@if(session('success'))
<div class="mb-4 flex items-start gap-2 rounded-xl border border-grass/25 bg-grass/10 p-4 text-sm text-grass">
    <i class="bi bi-check-circle-fill mt-0.5"></i> {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="mb-4 flex items-start gap-2 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill mt-0.5"></i> {{ $errors->first() }}
</div>
@endif

<div x-data="{ tab: 'plan-changes' }">
    <!-- Tabs -->
    <div class="flex flex-wrap gap-1.5 rounded-xl border border-ink/10 bg-paper-deep/60 p-1.5">
        @foreach($tabs as $key => $t)
        <button type="button" @click="tab = '{{ $key }}'"
            class="flex items-center gap-2 rounded-lg px-3.5 py-2 text-sm font-semibold transition-colors"
            :class="tab === '{{ $key }}' ? 'bg-brand text-white shadow-soft' : 'text-slate hover:bg-brand/5 hover:text-ink'">
            <i class="bi {{ $t['icon'] }}"></i> {{ $t['label'] }}
            @if($t['count'] > 0)
            <span class="rounded-full px-1.5 py-0.5 font-mono text-[11px]" :class="tab === '{{ $key }}' ? 'bg-white/20 text-white' : 'bg-mango/20 text-mango-deep'">{{ $t['count'] }}</span>
            @endif
        </button>
        @endforeach
    </div>

    <!-- ===== PLAN CHANGES ===== -->
    <div x-show="tab === 'plan-changes'" class="mt-5 space-y-4">
        @forelse($planChanges as $req)
        <div class="os-card p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-ink">{{ $req->user?->name ?? 'N/A' }}</span>
                        <span class="text-xs text-slate">· order {{ $req->order?->order_number }}</span>
                        @php $pchip = $req->status === 'pending' ? 'mango' : ($req->status === 'approved' ? 'grass' : 'ember'); @endphp
                        <span class="os-chip os-chip-{{ $pchip }}">{{ ucfirst($req->status) }}</span>
                    </div>
                    <p class="mt-1.5 text-sm text-slate">
                        <i class="bi bi-arrow-right text-mango-deep"></i>
                        {{ $req->currentPlan?->name ?? 'No plan' }} → <span class="font-semibold text-ink">{{ $req->requestedPlan?->name ?? 'N/A' }}</span>
                    </p>
                    @if($req->reason)
                        <p class="mt-2 rounded-lg bg-paper-deep/70 px-3 py-2 text-sm text-slate"><span class="font-semibold text-ink">Reason:</span> {{ $req->reason }}</p>
                    @endif
                    @if($req->admin_notes)
                        <p class="mt-1.5 text-xs text-slate"><span class="font-semibold text-brand">Admin note:</span> {{ $req->admin_notes }}</p>
                    @endif
                </div>
                @if($req->status === 'pending')
                <div class="flex shrink-0 items-end gap-2">
                    <form action="{{ route('admin.requests.plan-changes.approve', $req) }}" method="POST">
                        @csrf
                        <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-check-lg"></i> Approve</button>
                    </form>
                    <form action="{{ route('admin.requests.plan-changes.reject', $req) }}" method="POST" class="flex items-end gap-1.5">
                        @csrf
                        <input type="text" name="admin_notes" placeholder="Reject note…" class="os-input os-input-sm w-40">
                        <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-x-lg"></i> Reject</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p class="os-card p-8 text-center text-sm text-slate">No plan change requests.</p>
        @endforelse
    </div>

    <!-- ===== EXCHANGES ===== -->
    <div x-show="tab === 'exchanges'" class="mt-5 space-y-4" x-cloak>
        @forelse($exchanges as $req)
        <div class="os-card p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-ink">{{ $req->user?->name ?? 'N/A' }}</span>
                        <span class="text-xs text-slate">· order {{ $req->order?->order_number }}</span>
                        @php $echip = $req->status === 'pending' ? 'mango' : ($req->status === 'approved' ? 'grass' : 'ember'); @endphp
                        <span class="os-chip os-chip-{{ $echip }}">{{ ucfirst($req->status) }}</span>
                    </div>
                    <p class="mt-1.5 text-sm text-slate">
                        <i class="bi bi-arrow-left-right text-mango-deep"></i>
                        {{ $req->currentProduct?->name ?? 'Current product' }} → <span class="font-semibold text-ink">{{ $req->requestedProduct?->name ?? 'N/A' }}</span>
                    </p>
                    @if($req->reason)
                        <p class="mt-2 rounded-lg bg-paper-deep/70 px-3 py-2 text-sm text-slate"><span class="font-semibold text-ink">Reason:</span> {{ $req->reason }}</p>
                    @endif
                    @if($req->admin_notes)
                        <p class="mt-1.5 text-xs text-slate"><span class="font-semibold text-brand">Admin note:</span> {{ $req->admin_notes }}</p>
                    @endif
                </div>
                @if($req->status === 'pending')
                <form action="{{ route('admin.requests.exchange-requests.update', $req) }}" method="POST" class="flex shrink-0 flex-wrap items-end gap-1.5">
                    @csrf
                    <input type="hidden" name="status" value="approved">
                    <input type="text" name="admin_notes" placeholder="Note…" class="os-input os-input-sm w-36">
                    <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-check-lg"></i> Approve</button>
                </form>
                <form action="{{ route('admin.requests.exchange-requests.update', $req) }}" method="POST" class="flex shrink-0 items-end gap-1.5">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <input type="text" name="admin_notes" placeholder="Reject note…" class="os-input os-input-sm w-36">
                    <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-x-lg"></i> Reject</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <p class="os-card p-8 text-center text-sm text-slate">No exchange requests.</p>
        @endforelse
    </div>

    <!-- ===== PRODUCT REQUESTS ===== -->
    <div x-show="tab === 'product-requests'" class="mt-5 space-y-4" x-cloak>
        @forelse($productRequests as $req)
        <div class="os-card p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-ink">{{ $req->user?->name ?? 'N/A' }}</span>
                        @php $prchip = $req->status === 'pending' ? 'mango' : ($req->status === 'approved' ? 'grass' : ($req->status === 'under_review' ? 'brand' : 'ember')); @endphp
                        <span class="os-chip os-chip-{{ $prchip }}">{{ ucwords(str_replace('_', ' ', $req->status)) }}</span>
                    </div>
                    <p class="mt-1.5 text-sm font-semibold text-ink">{{ $req->name }}</p>
                    @if($req->description)
                        <p class="mt-1 text-sm text-slate">{{ Str::limit($req->description, 140) }}</p>
                    @endif
                    @if($req->reason)
                        <p class="mt-2 rounded-lg bg-paper-deep/70 px-3 py-2 text-sm text-slate"><span class="font-semibold text-ink">Why:</span> {{ $req->reason }}</p>
                    @endif
                    @if($req->link)
                        <a href="{{ $req->link }}" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-brand hover:underline"><i class="bi bi-box-arrow-up-right"></i> {{ Str::limit($req->link, 60) }}</a>
                    @endif
                    @if($req->admin_notes)
                        <p class="mt-1.5 text-xs text-slate"><span class="font-semibold text-brand">Admin note:</span> {{ $req->admin_notes }}</p>
                    @endif
                </div>
                @if(in_array($req->status, ['pending', 'under_review']))
                <div class="flex shrink-0 flex-wrap gap-1.5">
                    <form action="{{ route('admin.requests.product-requests.update', $req) }}" method="POST" class="flex items-end gap-1.5">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <input type="text" name="admin_notes" placeholder="Note…" class="os-input os-input-sm w-36">
                        <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-check-lg"></i> Approve</button>
                    </form>
                    <form action="{{ route('admin.requests.product-requests.update', $req) }}" method="POST" class="flex items-end gap-1.5">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <input type="text" name="admin_notes" placeholder="Reject note…" class="os-input os-input-sm w-36">
                        <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-x-lg"></i> Reject</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p class="os-card p-8 text-center text-sm text-slate">No product requests.</p>
        @endforelse
    </div>

    <!-- ===== ACCOUNT CLOSURES ===== -->
    <div x-show="tab === 'deletions'" class="mt-5 space-y-4" x-cloak>
        @forelse($deletions as $req)
        <div class="os-card p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-ink">{{ $req->user?->name ?? 'N/A' }}</span>
                        <span class="text-xs text-slate">· {{ $req->user?->email }}</span>
                        @php $dchip = $req->status === 'pending' ? 'mango' : ($req->status === 'approved' ? 'grass' : 'ember'); @endphp
                        <span class="os-chip os-chip-{{ $dchip }}">{{ ucfirst($req->status) }}</span>
                    </div>
                    @if($req->reason)
                        <p class="mt-2 rounded-lg bg-paper-deep/70 px-3 py-2 text-sm text-slate"><span class="font-semibold text-ink">Reason:</span> {{ $req->reason }}</p>
                    @endif
                    @if($req->admin_notes)
                        <p class="mt-1.5 text-xs text-slate"><span class="font-semibold text-brand">Admin note:</span> {{ $req->admin_notes }}</p>
                    @endif
                </div>
                @if($req->status === 'pending')
                <div class="flex shrink-0 items-end gap-2">
                    <form action="{{ route('admin.requests.deletion-requests.approve', $req) }}" method="POST"
                          onsubmit="return confirm('Approve account closure? The customer account will be deactivated.')">
                        @csrf
                        <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-check-lg"></i> Approve closure</button>
                    </form>
                    <form action="{{ route('admin.requests.deletion-requests.reject', $req) }}" method="POST" class="flex items-end gap-1.5">
                        @csrf
                        <input type="text" name="admin_notes" placeholder="Reject note…" class="os-input os-input-sm w-40">
                        <button type="submit" class="os-btn os-btn-ghost os-btn-sm text-ember"><i class="bi bi-x-lg"></i> Reject</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p class="os-card p-8 text-center text-sm text-slate">No account closure requests.</p>
        @endforelse
    </div>
</div>

@endsection
