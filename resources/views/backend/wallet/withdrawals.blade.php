@extends('backend.layouts.console')
@section('title', 'Withdrawal Requests — '.storeName().' Admin')
@section('page_title', 'Withdrawal Requests')

@section('content')

@if(session('success'))
<div class="mb-4 flex items-start gap-2 rounded-xl border border-grass/25 bg-grass/10 p-4 text-sm text-grass">
    <i class="bi bi-check-circle-fill mt-0.5"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 flex items-start gap-2 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill mt-0.5"></i> {{ session('error') }}
</div>
@endif

<div class="flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="font-display text-lg font-bold text-ink">Withdrawal requests</h2>
        <p class="mt-0.5 text-sm text-slate">Review and action customer bank withdrawals. Funds are held until you complete or fail the request.</p>
    </div>
    <div class="flex gap-2">
        @foreach(['pending', 'processing', 'completed', 'failed'] as $s)
        <a href="{{ route('admin.wallet.withdrawals', ['status' => $s]) }}"
           class="os-btn os-btn-sm {{ request('status') === $s ? 'os-btn-brand' : 'os-btn-ghost' }}">
            {{ ucfirst($s) }}
        </a>
        @endforeach
    </div>
</div>

<div class="mt-5 space-y-4">
    @forelse($requests as $w)
    <div class="os-card p-5">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <p class="text-sm font-semibold text-ink">{{ $w->user?->name ?? 'Deleted user' }}</p>
                    <span class="os-chip {{ $w->status === 'completed' ? 'os-chip-grass' : ($w->status === 'failed' ? 'os-chip-ember' : 'os-chip-brand') }}">
                        {{ ucfirst($w->status) }}
                    </span>
                    <span class="text-xs text-slate">#{{ $w->id }}</span>
                </div>
                <p class="mt-1 text-xs text-slate">
                    <i class="bi bi-bank"></i> {{ $w->bankAccount?->bank_name ?? '—' }} •••• {{ substr($w->bankAccount?->account_number ?? '', -4) }}
                    · {{ $w->created_at->format('M d, Y h:i A') }}
                </p>
                @if($w->admin_note)
                <p class="mt-2 rounded-lg bg-paper px-3 py-2 text-xs text-slate">Note: {{ $w->admin_note }}</p>
                @endif
            </div>
            <div class="text-right">
                <p class="font-mono text-sm text-ink">₦{{ number_format($w->amount, 2) }}</p>
                <p class="mt-0.5 text-xs text-ember">Fee ₦{{ number_format($w->fee, 2) }}</p>
                <p class="mt-0.5 font-mono text-sm font-semibold text-grass">Net ₦{{ number_format($w->net_amount, 2) }}</p>
            </div>
        </div>

        @if(!in_array($w->status, ['completed', 'failed']))
        <form action="{{ route('admin.wallet.withdrawals.update', $w) }}" method="POST" class="mt-4 flex flex-wrap items-center gap-3 border-t border-ink/5 pt-4">
            @csrf
            <input type="text" name="admin_note" placeholder="Admin note (optional)" class="os-input min-w-40 flex-1">
            <button type="submit" name="status" value="processing" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-hourglass-split"></i> Processing</button>
            <button type="submit" name="status" value="completed" class="os-btn os-btn-success os-btn-sm" onclick="return confirm('Mark this withdrawal as completed? The funds stay with the bank transfer.')"><i class="bi bi-check-lg"></i> Completed</button>
            <button type="submit" name="status" value="failed" class="os-btn os-btn-danger os-btn-sm" onclick="return confirm('Fail this withdrawal? The held funds return to the customer\'s withdrawable balance.')"><i class="bi bi-x-lg"></i> Failed</button>
        </form>
        @endif
    </div>
    @empty
    <div class="os-card p-10 text-center">
        <i class="bi bi-inbox text-3xl text-slate/40"></i>
        <p class="mt-3 text-sm text-slate">No withdrawal requests {{ request('status') ? 'with status "' . request('status') . '"' : '' }}.</p>
    </div>
    @endforelse
</div>

<div class="mt-4">{{ $requests->withQueryString()->links() }}</div>

@endsection
