@extends('backend.layouts.console')
@section('title', 'Customer — '.($user->name ?? 'N/A').' | '.storeName().' Admin')
@section('page_title', 'Customer: '.($user->name ?? 'N/A'))

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', [
        'crumbs' => [
            ['label' => 'Customers', 'route' => 'admin.users.index'],
            ['label' => $user->name ?? 'Customer'],
        ],
    ])
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

<!-- Header -->
<div class="os-card mb-6 p-5">
    <div class="flex flex-wrap items-center gap-4">
        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand font-display text-xl font-bold text-white">{{ strtoupper(substr($user->name ?? '?', 0, 1)) }}</span>
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <h2 class="font-display text-lg font-bold text-ink">{{ $user->name ?? 'N/A' }}</h2>
                @if($user->is_suspended)
                    <span class="os-chip os-chip-ember"><i class="bi bi-lock-fill"></i> Suspended</span>
                @elseif(!$user->is_active)
                    <span class="os-chip os-chip-brand">Inactive</span>
                @else
                    <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> Active</span>
                @endif
                @foreach($user->getRoleNames() as $rn)
                    <span class="os-chip {{ $rn === 'Super Admin' ? 'os-chip-mango' : 'os-chip-brand' }}">{{ $rn }}</span>
                @endforeach
            </div>
            <p class="mt-1 text-sm text-slate">{{ $user->email }} · {{ $user->phone ?? 'no phone' }} · joined {{ $user->created_at->format('M j, Y') }}</p>
            @if($overdueCount > 0)
                <p class="mt-1 text-xs font-semibold text-ember"><i class="bi bi-exclamation-triangle-fill"></i> {{ $overdueCount }} order(s) with overdue installments</p>
            @endif
        </div>
        <div class="flex flex-wrap gap-2">
            <form action="{{ route('admin.users.reminder', $user) }}" method="POST">
                @csrf
                <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-bell-fill"></i> Send payment reminder</button>
            </form>
            @if($user->is_suspended)
                <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="os-btn os-btn-ghost"><i class="bi bi-unlock-fill"></i> Unsuspend</button>
                </form>
            @else
                <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="os-btn os-btn-ghost"><i class="bi bi-lock-fill"></i> Suspend</button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-12">

    <!-- ===== LEFT COLUMN ===== -->
    <div class="space-y-6 lg:col-span-4">

        <!-- Wallet -->
        <div class="os-card p-5">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-wallet2 text-mango-deep"></i> Wallet</h3>
            <p class="mt-3 font-mono text-2xl font-semibold text-ink">{{ formatPrice($user->wallet?->balance ?? 0, 0) }}</p>
            <p class="mt-1 text-xs text-slate">
                Withdrawable: <span class="font-semibold text-grass">{{ formatPrice($user->wallet ? $user->wallet->withdrawableBalance() : 0, 0) }}</span>
            </p>
        </div>

        <!-- Verification -->
        <div class="os-card p-5">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-shield-check text-mango-deep"></i> Verification</h3>
            <ul class="mt-3 space-y-2.5">
                @php
                    $verifications = [
                        'Identity card' => $user->identity_verification ?? 'unverified',
                        'Payment card' => $user->payment_card_verification ?? 'unverified',
                        'Bank account' => $user->bank_account_verification ?? 'unverified',
                        'Delivery address' => $user->delivery_address_verification ?? 'unverified',
                        'Store terms' => $user->store_terms_acceptance ?? 'unverified',
                        'Email' => $user->email_verified_at ? 'verified' : 'unverified',
                    ];
                @endphp
                @foreach($verifications as $label => $status)
                <li class="flex items-center justify-between gap-3">
                    <span class="text-sm text-slate">{{ $label }}</span>
                    @php
                        $chip = $status === 'verified' || $status === 'approved' ? 'grass' : ($status === 'rejected' ? 'ember' : ($status === 'pending' ? 'mango' : 'brand'));
                    @endphp
                    <span class="os-chip os-chip-{{ $chip }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Support notes -->
        <div class="os-card p-5">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-journal-text text-mango-deep"></i> Support notes</h3>
            <p class="mt-1 text-xs text-slate">Internal staff notes — never shown to the customer.</p>
            <form action="{{ route('admin.users.support-notes', $user) }}" method="POST" class="mt-3">
                @csrf
                <textarea name="support_notes" rows="4" class="os-input w-full" placeholder="Add a note about this customer…">{{ $user->support_notes }}</textarea>
                <button type="submit" class="os-btn os-btn-brand os-btn-sm mt-2"><i class="bi bi-save-fill"></i> Save notes</button>
            </form>
        </div>

        <!-- Saved payment methods -->
        <div class="os-card p-5">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-credit-card text-mango-deep"></i> Saved payment methods</h3>
            <div class="mt-3 space-y-2">
                @forelse($user->savedCards as $card)
                    <div class="flex items-center justify-between rounded-lg border border-ink/10 px-3 py-2">
                        <span class="font-mono text-xs text-ink">•••• {{ $card->last_four }}</span>
                        <span class="text-[11px] text-slate">{{ strtoupper($card->brand ?? 'card') }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate">No saved cards</p>
                @endforelse
                @forelse($user->bankAccounts as $bank)
                    <div class="flex items-center justify-between rounded-lg border border-ink/10 px-3 py-2">
                        <span class="font-mono text-xs text-ink">•••• {{ $bank->last_four ?? substr($bank->account_number ?? '', -4) }}</span>
                        <span class="text-[11px] text-slate">{{ $bank->bank_name ?? 'bank' }}</span>
                    </div>
                @empty
                    @if($user->savedCards->isEmpty())
                        <p class="text-xs text-slate">No saved bank accounts</p>
                    @endif
                @endforelse
            </div>
        </div>
    </div>

    <!-- ===== RIGHT COLUMN ===== -->
    <div class="space-y-6 lg:col-span-8">

        <!-- Role assignment -->
        <div class="os-card p-5">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-person-badge text-mango-deep"></i> Role</h3>
            <form action="{{ route('admin.users.assign-role', $user) }}" method="POST" class="mt-3 flex flex-wrap items-end gap-2">
                @csrf
                <select name="role_id" class="os-input w-64" {{ $roles->isEmpty() ? 'disabled' : '' }}>
                    @forelse($roles as $r)
                        <option value="{{ $r->id }}" {{ $user->hasRole($r->name) ? 'selected' : '' }}>{{ $r->name }}</option>
                    @empty
                        <option value="">Run php artisan migrate --seed first</option>
                    @endforelse
                </select>
                <button type="submit" class="os-btn os-btn-brand os-btn-sm"><i class="bi bi-check-lg"></i> Assign role</button>
                <span class="text-[11px] text-slate">Super Admin assignment is restricted to Super Admins.</span>
            </form>
        </div>

        <!-- Plans / orders -->
        <div class="os-card overflow-hidden">
            <div class="flex items-center justify-between border-b border-ink/10 px-5 py-4">
                <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-calendar2-week text-mango-deep"></i> Payment plans</h3>
                @if($nextDue)
                    <span class="text-xs text-slate">Next due: <span class="font-semibold text-mango-deep">{{ formatPrice($nextDue->amount, 0) }}</span> · {{ $nextDue->due_date->format('M j, Y') }}</span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="os-table w-full">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Plan</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->orders as $o)
                        <tr>
                            <td class="font-mono text-xs text-slate">{{ $o->order_number }}</td>
                            <td>
                                @if($o->installmentPlan)
                                    <span class="os-chip">{{ $o->installmentPlan->name }}</span>
                                @else
                                    <span class="text-xs text-slate">Pay once</span>
                                @endif
                            </td>
                            <td class="font-mono text-sm text-ink">{{ formatPrice($o->grand_total ?? $o->total_amount, 0) }}</td>
                            <td class="font-mono text-sm text-grass">{{ formatPrice($o->paid_amount, 0) }}</td>
                            <td>
                                @php
                                    $total = (float) ($o->grand_total ?? $o->total_amount ?: 1);
                                    $pct = $total > 0 ? round(((float) $o->paid_amount / $total) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 w-24 overflow-hidden rounded-full bg-ink/10">
                                        <div class="h-full rounded-full bg-mango" style="width: {{ min($pct, 100) }}%"></div>
                                    </div>
                                    <span class="font-mono text-xs text-slate">{{ min($pct, 100) }}%</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $ochip = in_array($o->status, ['completed', 'fully_paid']) ? 'grass' : (in_array($o->status, ['cancelled', 'failed']) ? 'ember' : (in_array($o->status, ['pending', 'partial_paid']) ? 'mango' : 'brand'));
                                @endphp
                                <span class="os-chip os-chip-{{ $ochip }}">{{ ucwords(str_replace('_', ' ', $o->status)) }}</span>
                            </td>
                            <td><a href="{{ route('admin.orders.show', $o) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-arrow-right"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="py-8 text-center text-sm text-slate">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Open requests -->
        <div class="os-card p-5">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-inbox-fill text-mango-deep"></i> Open requests</h3>
            @php
                $open = $user->planChangeRequests->where('status', 'pending')->count()
                    + $user->exchangeRequests->where('status', 'pending')->count()
                    + $user->productRequests->where('status', 'pending')->count();
            @endphp
            @if($open > 0)
                <p class="mt-2 text-sm text-slate"><span class="font-semibold text-mango-deep">{{ $open }}</span> pending request(s) — review them from the <a href="{{ route('admin.requests.index') }}" class="font-semibold text-brand hover:underline">Requests</a> screen.</p>
            @else
                <p class="mt-2 text-sm text-slate">No pending requests.</p>
            @endif
        </div>

        <!-- Danger zone -->
        @can('permanentlyDelete', $user)
        <div class="os-card border border-ember/25 p-5">
            <h3 class="flex items-center gap-2 font-display text-sm font-bold text-ember"><i class="bi bi-exclamation-octagon-fill"></i> Danger zone</h3>
            <p class="mt-1 text-xs text-slate">Permanently erase this account and all of its data. This cannot be undone. Super Admin only.</p>
            <form action="{{ route('admin.users.permanent-delete', $user) }}" method="POST" class="mt-3"
                  onsubmit="return confirm('Permanently delete {{ addslashes($user->name ?? 'this user') }} and ALL their data? This cannot be undone.')">
                @csrf
                <button type="submit" class="os-btn os-btn-danger os-btn-sm"><i class="bi bi-trash-fill"></i> Delete permanently</button>
            </form>
        </div>
        @endcan
    </div>
</div>

@endsection
