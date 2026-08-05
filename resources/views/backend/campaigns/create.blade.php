@extends('backend.layouts.console')
@section('title', 'New Campaign — '.storeName().' Admin')
@section('page_title', 'New Campaign')
@section('breadcrumbs')
    @include('backend.partials.breadcrumbs', ['crumbs' => [['label' => 'Campaigns', 'route' => 'admin.campaigns.index'], ['label' => 'New Campaign']]])
@endsection

@section('content')
@if($errors->any())
<div class="mb-4 rounded-xl border border-ember/25 bg-ember/10 p-4 text-sm text-ember">
    <i class="bi bi-exclamation-circle-fill me-1"></i>
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

<form action="{{ route('admin.campaigns.store') }}" method="POST"
      x-data="campaignComposer({
        templates: @json($templates->map(fn ($t) => ['id' => $t->id, 'name' => $t->name, 'subject' => $t->subject, 'content' => $t->content])),
        counts: @json($segmentCounts)
      })">
    @csrf

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- ===== Compose ===== -->
        <div class="space-y-6 lg:col-span-2">
            <div class="os-card p-6">
                <h2 class="flex items-center gap-2 font-display text-lg font-bold text-ink"><i class="bi bi-pencil-square text-mango-deep"></i> Compose</h2>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Start from template</label>
                        <select x-model="templateId" @change="applyTemplate()" class="os-input">
                            <option value="">— Blank —</option>
                            <template x-for="t in templates" :key="t.id">
                                <option :value="t.id" x-text="t.name"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Campaign name <span class="text-ember">*</span></label>
                        <input type="text" name="name" class="os-input" value="{{ old('name') }}" required placeholder="e.g. Summer Sale 2026" x-model="name">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Subject line</label>
                        <input type="text" name="subject" class="os-input" value="{{ old('subject') }}" placeholder="e.g. Don't miss our biggest sale!" x-model="subject">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Message <span class="text-ember">*</span></label>
                        <textarea name="content" rows="9" class="os-input" required placeholder="Write your campaign message here… URLs are auto-tracked." x-model="content">{{ old('content') }}</textarea>
                        <p class="mt-1.5 flex items-center gap-1 text-[11px] text-slate"><i class="bi bi-magic"></i> Plain text is fine — links are wrapped with click tracking and a branded layout is added automatically.</p>
                    </div>
                </div>
            </div>

            <div class="os-card p-6">
                <h2 class="flex items-center gap-2 font-display text-lg font-bold text-ink"><i class="bi bi-crosshair text-mango-deep"></i> Audience</h2>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Segment <span class="text-ember">*</span></label>
                        <select name="audience" class="os-input" x-model="audience">
                            <option value="all">All customers</option>
                            <option value="active_users">Active customers</option>
                            <option value="overdue_users">Customers with overdue payments</option>
                            <option value="plan_users">Customers on an installment plan</option>
                            <option value="repeat_customers">Repeat customers (2+ orders)</option>
                        </select>
                    </div>
                    <div class="rounded-xl border border-ink/10 bg-paper px-4 py-3">
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate">Estimated recipients</p>
                        <p class="font-mono text-xl font-semibold text-ink" x-text="recipientCount()"></p>
                    </div>

                    <div x-show="audience === 'plan_users'" x-cloak class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Limit to a plan (optional)</label>
                        <select name="plan_id" class="os-input">
                            <option value="">All plans</option>
                            @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ old('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }} ({{ $plan->duration }}×, {{ ucfirst($plan->type) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="audience === 'repeat_customers'" x-cloak>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Minimum orders</label>
                        <input type="number" name="min_orders" min="2" max="999" class="os-input" value="{{ old('min_orders', 2) }}">
                    </div>
                </div>
            </div>

            <div class="os-card p-6">
                <h2 class="flex items-center gap-2 font-display text-lg font-bold text-ink"><i class="bi bi-calendar2-check text-mango-deep"></i> Delivery</h2>

                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Channel <span class="text-ember">*</span></label>
                        <select name="channel" class="os-input">
                            <option value="email" {{ old('channel') === 'sms' ? '' : 'selected' }}>Email</option>
                            <option value="sms" {{ old('channel') === 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="both" {{ old('channel') === 'both' ? 'selected' : '' }}>Email + SMS</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">When <span class="text-ember">*</span></label>
                        <select name="action" class="os-input" x-model="action">
                            <option value="draft">Save as draft</option>
                            <option value="send_now">Send now</option>
                            <option value="schedule">Schedule…</option>
                        </select>
                    </div>
                    <div x-show="action === 'schedule'" x-cloak>
                        <label class="mb-1.5 block text-xs font-semibold text-slate">Send at</label>
                        <input type="datetime-local" name="scheduled_at" class="os-input" value="{{ old('scheduled_at') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== Sidebar ===== -->
        <div class="space-y-6">
            <div class="os-card p-6">
                <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-magic text-mango-deep"></i> Save as template</h2>
                <label class="mt-4 flex cursor-pointer items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="save_template" value="1" class="h-4 w-4 accent-mango" x-model="saveTemplate">
                    Save this message as a reusable template
                </label>
                <div x-show="saveTemplate" x-cloak class="mt-3">
                    <label class="mb-1.5 block text-xs font-semibold text-slate">Template name</label>
                    <input type="text" name="template_name" class="os-input" placeholder="e.g. Payment reminder style" x-model="templateName">
                </div>
            </div>

            <div class="os-card p-6">
                <h2 class="flex items-center gap-2 font-display text-sm font-bold text-ink"><i class="bi bi-people-fill text-mango-deep"></i> Segment sizes</h2>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li class="flex justify-between"><span class="text-slate">All customers</span><span class="money" x-text="counts.all"></span></li>
                    <li class="flex justify-between"><span class="text-slate">Active customers</span><span class="money" x-text="counts.active_users"></span></li>
                    <li class="flex justify-between"><span class="text-slate">Overdue payments</span><span class="money" x-text="counts.overdue_users"></span></li>
                    <li class="flex justify-between"><span class="text-slate">On an installment plan</span><span class="money" x-text="counts.plan_users"></span></li>
                    <li class="flex justify-between"><span class="text-slate">Repeat customers</span><span class="money" x-text="counts.repeat_customers"></span></li>
                </ul>
                <p class="mt-3 border-t border-ink/10 pt-3 text-[11px] leading-relaxed text-slate"><i class="bi bi-info-circle me-1"></i>Recipients are snapshotted into a log when the queued send runs, so metrics survive account edits.</p>
            </div>

            <button type="submit" class="os-btn os-btn-mango w-full text-base"><i class="bi bi-send-fill"></i> Save &amp; continue</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function campaignComposer({ templates, counts }) {
    return {
        templates,
        counts,
        templateId: '',
        name: '',
        subject: '',
        content: '',
        audience: 'all',
        action: 'draft',
        saveTemplate: false,
        templateName: '',
        applyTemplate() {
            const t = this.templates.find((x) => x.id == this.templateId);
            if (!t) return;
            this.name = t.name;
            this.subject = t.subject || '';
            this.content = t.content;
        },
        recipientCount() {
            return Number(this.counts[this.audience] ?? 0).toLocaleString();
        },
    };
}
</script>
@endpush
