@extends('backend.layouts.console')
@section('title', 'Import Products — '.storeName().' Admin')
@section('page_title', 'Import Products')

@section('content')

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

<div class="grid gap-6 lg:grid-cols-2">
    <!-- ===== UPLOAD ===== -->
    <div class="os-card p-6">
        <h2 class="font-display text-lg font-bold text-ink">Upload product CSV</h2>
        <p class="mt-1 text-sm text-slate">Import products in bulk. Files up to 200 rows import instantly with a per-row report; larger files are queued.</p>

        <form action="{{ route('admin.products.import.store') }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
            @csrf
            <div>
                <label for="csv" class="mb-1.5 block text-xs font-semibold text-slate">CSV file</label>
                <input type="file" name="csv" id="csv" accept=".csv,.txt" class="os-input w-full" required>
            </div>
            <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-upload"></i> Upload &amp; Import</button>
        </form>

        <div class="mt-6 rounded-xl border border-ink/10 bg-paper p-4">
            <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate">Expected columns</p>
            <p class="mt-2 font-mono text-[11px] leading-relaxed text-slate">Product Name, Price, Description, Category, Images (URLs), Supplier Name, Supplier Phone, Supplier Email, Supplier Address</p>
            <p class="mt-2 text-[11px] text-slate"><i class="bi bi-info-circle-fill mr-1 text-brand"></i> Images: pipe-separated (|) or comma-separated URLs — the first becomes the primary image. Supplier columns are optional; when supplied the supplier is created automatically (admin-only data, never shown to customers).</p>
        </div>
    </div>

    <!-- ===== PREVIOUS IMPORTS ===== -->
    <div class="os-card p-6">
        <h2 class="font-display text-lg font-bold text-ink">Import history</h2>
        <div class="mt-4 divide-y divide-ink/5">
            @forelse($imports as $import)
            <div class="flex items-center justify-between gap-3 py-3">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-ink">{{ $import->file_name }}</p>
                    <p class="mt-0.5 text-xs text-slate">{{ $import->created_at->diffForHumans() }} · {{ $import->total_rows }} rows</p>
                    <p class="mt-1 text-xs">
                        <span class="font-mono text-grass">{{ $import->success_rows }} ok</span>
                        <span class="mx-1 text-ink/20">·</span>
                        <span class="font-mono text-ember">{{ $import->error_rows }} errors</span>
                    </p>
                </div>
                <div class="flex flex-shrink-0 items-center gap-2">
                    @if($import->status === 'pending' || $import->status === 'processing')
                        <span class="os-chip os-chip-brand"><i class="bi bi-arrow-repeat"></i> {{ ucfirst($import->status) }}</span>
                    @elseif($import->status === 'failed')
                        <span class="os-chip os-chip-ember">{{ ucfirst($import->status) }}</span>
                    @else
                        <a href="{{ route('admin.products.import.report', $import) }}" class="os-btn os-btn-ghost os-btn-sm"><i class="bi bi-list-check"></i> Report</a>
                    @endif
                </div>
            </div>
            @empty
            <p class="py-6 text-center text-sm text-slate">No imports yet.</p>
            @endforelse
        </div>
        @if($imports->hasPages())
            <div class="mt-4">{{ $imports->links() }}</div>
        @endif
    </div>
</div>

@endsection
