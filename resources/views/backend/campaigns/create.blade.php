@extends('backend.app')
@section('title', 'New Campaign — OwnPace Admin')
@section('page_title', 'New Campaign')

@section('content')
<div class="fp-table-wrap">
    <div class="fp-table-header"><h5>Create Campaign</h5></div>
    <div style="padding:24px;">
        @if($errors->any())
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:12px 16px;margin-bottom:20px;color:#ef4444;font-size:13px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif

        <form action="{{ route('admin.campaigns.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Campaign Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="name" class="fp-form-control" value="{{ old('name') }}" required placeholder="e.g. Summer Sale 2026">
                </div>
                <div class="col-sm-6">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Subject Line</label>
                    <input type="text" name="subject" class="fp-form-control" value="{{ old('subject') }}" placeholder="e.g. Don't miss our biggest sale!">
                </div>
                <div class="col-12">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Message Content <span style="color:#ef4444;">*</span></label>
                    <textarea name="content" class="fp-form-control" rows="8" required placeholder="Write your campaign message here...">{{ old('content') }}</textarea>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Channel <span style="color:#ef4444;">*</span></label>
                    <select name="channel" class="fp-form-control">
                        <option value="email" {{ old('channel')=='email'?'selected':'' }}>Email</option>
                        <option value="sms" {{ old('channel')=='sms'?'selected':'' }}>SMS</option>
                        <option value="both" {{ old('channel')=='both'?'selected':'' }}>Email + SMS</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Target Audience <span style="color:#ef4444;">*</span></label>
                    <select name="audience" class="fp-form-control">
                        <option value="all" {{ old('audience')=='all'?'selected':'' }}>All Customers</option>
                        <option value="active_users" {{ old('audience')=='active_users'?'selected':'' }}>Active Customers</option>
                        <option value="overdue_users" {{ old('audience')=='overdue_users'?'selected':'' }}>Overdue Payments</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:6px;">Action</label>
                    <select name="action" class="fp-form-control">
                        <option value="draft">Save as Draft</option>
                        <option value="send_now">Save & Send Now</option>
                    </select>
                </div>
                <div class="col-12" style="margin-top:16px;">
                    <button type="submit" class="fp-btn fp-btn-gold"><i class="bi bi-check-lg"></i> Save Campaign</button>
                    <a href="{{ route('admin.campaigns.index') }}" class="fp-btn fp-btn-ghost" style="margin-left:8px;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
