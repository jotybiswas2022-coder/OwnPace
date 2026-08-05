@extends('backend.layouts.console')
@section('title', 'Edit Supplier — '.storeName().' Admin')
@section('page_title', 'Edit Supplier')

@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Shop', 'route' => 'admin.suppliers.index'], ['label' => $supplier->name]]])
@endsection

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="os-card overflow-hidden">
        <div class="border-b border-ink/10 px-6 py-4">
            <h3 class="font-display text-sm font-bold text-ink">Edit: {{ $supplier->name }}</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="sup_name" class="os-label">Company Name <span class="text-ember">*</span></label>
                        <input type="text" id="sup_name" name="name" class="os-input w-full" value="{{ $supplier->name }}" required>
                    </div>
                    <div>
                        <label for="sup_contact" class="os-label">Contact Person</label>
                        <input type="text" id="sup_contact" name="contact_person" class="os-input w-full" value="{{ $supplier->contact_person }}">
                    </div>
                    <div>
                        <label for="sup_email" class="os-label">Email</label>
                        <input type="email" id="sup_email" name="email" class="os-input w-full" value="{{ $supplier->email }}">
                    </div>
                    <div>
                        <label for="sup_phone" class="os-label">Phone</label>
                        <input type="text" id="sup_phone" name="phone" class="os-input w-full" value="{{ $supplier->phone }}">
                    </div>
                </div>
                <div>
                    <label for="sup_address" class="os-label">Address</label>
                    <textarea id="sup_address" name="address" class="os-input w-full" rows="3">{{ $supplier->address }}</textarea>
                </div>
                <div class="flex items-center gap-3 border-t border-ink/10 pt-5">
                    <button type="submit" class="os-btn os-btn-brand"><i class="bi bi-check-lg"></i> Update Supplier</button>
                    <a href="{{ route('admin.suppliers.index') }}" class="os-btn os-btn-ghost"><i class="bi bi-arrow-left"></i> Back</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
