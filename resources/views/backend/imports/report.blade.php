@extends('backend.layouts.console')
@section('title', 'Import Report — '.storeName().' Admin')
@section('page_title', 'Import Report')

@section('content')

<div class="mb-4 flex flex-wrap items-center justify-between gap-3">
    <div>
        <a href="{{ route('admin.products.import') }}" class="text-sm font-semibold text-brand hover:text-ink/80"><i class="bi bi-arrow-left"></i> Back to imports</a>
        <h2 class="mt-1 font-display text-lg font-bold text-ink">{{ $import->file_name }}</h2>
        <p class="mt-0.5 text-sm text-slate">{{ $import->created_at->format('M d, Y H:i') }} · {{ $import->total_rows }} rows · <span class="font-mono text-grass">{{ $import->success_rows }} ok</span> · <span class="font-mono text-ember">{{ $import->error_rows }} errors</span></p>
    </div>
    @if($import->status === 'pending' || $import->status === 'processing')
        <span class="os-chip os-chip-brand"><i class="bi bi-arrow-repeat"></i> {{ ucfirst($import->status) }} — refresh to see progress</span>
    @elseif($import->status === 'failed')
        <span class="os-chip os-chip-ember"><i class="bi bi-x-circle-fill"></i> Failed: {{ $import->error }}</span>
    @endif
</div>

@if($import->status === 'completed')
<div class="os-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="os-table w-full">
            <thead>
                <tr>
                    <th>Row</th>
                    <th>Status</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @forelse($import->report ?? [] as $line)
                <tr>
                    <td data-label="Row" class="font-mono text-xs text-slate">#{{ $line['row'] ?? '—' }}</td>
                    <td data-label="Status">
                        @if(($line['status'] ?? '') === 'success')
                            <span class="os-chip os-chip-grass"><i class="bi bi-check-circle-fill"></i> Success</span>
                        @else
                            <span class="os-chip os-chip-ember"><i class="bi bi-x-circle-fill"></i> Error</span>
                        @endif
                    </td>
                    <td data-label="Message" class="text-sm text-ink/80">{{ $line['message'] ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="py-6 text-center text-sm text-slate">No rows processed.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@elseif($import->status === 'failed')
<div class="os-card p-6 text-sm text-ember">
    <i class="bi bi-exclamation-triangle-fill mr-1"></i> {{ $import->error }}
</div>
@endif

@endsection
