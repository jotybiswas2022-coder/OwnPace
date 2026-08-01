<div class="fp-chk-breakdown" x-data="{ expanded: true }">
    <label style="display:block;font-size:12px;color:var(--text-dim);margin-bottom:8px;font-weight:600;">
        Select Installment Plan
    </label>
    <select
        name="installment_plan_id"
        wire:model.live="planId"
        class="fp-chk-select"
        aria-label="Installment plan"
    >
        <option value="0">Choose a plan</option>
        @foreach($this->plans as $plan)
            <option value="{{ $plan->id }}">{{ $plan->name }} · {{ $plan->interest_rate }}% interest · {{ $plan->cadence }}</option>
        @endforeach
    </select>
    @error('installment_plan_id')<small class="fp-chk-field-error">{{ $message }}</small>@enderror

    @if($this->insurance && $this->insurance->is_enabled)
        <label class="fp-chk-checkbox" style="margin-top:16px;">
            <input type="checkbox" name="has_insurance" value="1" wire:model.live="hasInsurance">
            <span style="color:var(--text-muted);font-size:14px;">
                Add Insurance <span style="color:var(--gold-400);font-weight:600;">({{ $this->insurance->rate }}% of total)</span>
            </span>
        </label>
        <p style="color:var(--text-dim);font-size:12px;margin:6px 0 0 30px;">Protect your purchase against damage, loss, or theft.</p>
    @endif

    {{-- ===== LIVE BREAKDOWN ===== --}}
    @if($this->breakdown)
        <div class="fp-chk-bd" wire:key="bd-{{ $this->planId }}-{{ $this->hasInsurance ? 1 : 0 }}">
            <div class="fp-chk-bd-line">
                <span>Item total</span>
                <span class="fp-mono">₦{{ number_format($this->breakdown['principal'], 2) }}</span>
            </div>
            @if($this->breakdown['interest'] > 0)
            <div class="fp-chk-bd-line">
                <span>Interest ({{ $this->breakdown['interest_rate'] }}%)</span>
                <span class="fp-mono">+ ₦{{ number_format($this->breakdown['interest'], 2) }}</span>
            </div>
            @endif
            <div class="fp-chk-bd-line">
                <span>Shipping</span>
                <span class="fp-mono">+ ₦{{ number_format($this->breakdown['shipping_fee'], 2) }}</span>
            </div>
            @if($this->breakdown['has_insurance'])
            <div class="fp-chk-bd-line">
                <span>Insurance ({{ $this->insurance->rate }}%)</span>
                <span class="fp-mono">+ ₦{{ number_format($this->breakdown['insurance_fee'], 2) }}</span>
            </div>
            @endif
            <div class="fp-chk-bd-divider"></div>
            <div class="fp-chk-bd-line fp-chk-bd-total">
                <span>Total to pay back</span>
                <span class="fp-mono">₦{{ number_format($this->breakdown['grand_total'], 2) }}</span>
            </div>
            <div class="fp-chk-bd-per">
                <span class="fp-mono fp-chk-bd-per-amount">₦{{ number_format($this->breakdown['per_installment'], 2) }}</span>
                <span>per {{ $this->breakdown['type'] === 'weekly' ? 'week' : 'month' }} × {{ $this->breakdown['duration'] }}</span>
            </div>
        </div>
    @else
        <div class="fp-chk-bd-empty">
            <i class="bi bi-calculator"></i>
            <span>Pick a plan above to see your payment breakdown.</span>
        </div>
    @endif
</div>

@push('styles')
<style>
.fp-chk-bd {
    margin-top: 18px; padding: 18px;
    background: var(--surface-dark);
    border: 1px solid rgba(234,179,8,0.15);
    border-radius: var(--radius-sm);
    animation: bdIn 0.3s ease;
}
@keyframes bdIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
.fp-chk-bd-line {
    display: flex; justify-content: space-between; align-items: center;
    font-size: 13px; color: var(--text-muted); margin-bottom: 8px;
}
.fp-chk-bd-divider {
    height: 1px; background: var(--card-border); margin: 12px 0;
}
.fp-chk-bd-total {
    font-size: 15px; font-weight: 700; color: var(--text-primary);
}
.fp-chk-bd-total .fp-mono { color: var(--gold-400); }
.fp-mono {
    font-family: 'IBM Plex Mono', ui-monospace, monospace;
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.2px;
}
.fp-chk-bd-per {
    display: flex; align-items: baseline; gap: 8px;
    margin-top: 14px; padding-top: 14px; border-top: 1px dashed var(--card-border);
    color: var(--text-dim); font-size: 12px; flex-wrap: wrap;
}
.fp-chk-bd-per-amount {
    font-size: 22px; font-weight: 700; color: var(--gold-400);
}
.fp-chk-bd-empty {
    display: flex; align-items: center; gap: 10px;
    margin-top: 16px; padding: 16px;
    background: var(--surface-dark); border: 1px dashed var(--card-border);
    border-radius: var(--radius-sm);
    color: var(--text-dim); font-size: 13px;
}
.fp-chk-bd-empty i { color: var(--gold-500); font-size: 16px; }
</style>
@endpush
