<div class="mt-5">
    <label for="checkout-plan" class="os-label">Select installment plan</label>
    <select
        id="checkout-plan"
        name="installment_plan_id"
        wire:model.live="planId"
        class="os-input"
        aria-label="Installment plan"
    >
        <option value="0">Choose a plan</option>
        @foreach($this->plans as $plan)
            <option value="{{ $plan->id }}">{{ $plan->name }} · {{ $plan->interest_rate }}% interest · {{ $plan->cadence }}</option>
        @endforeach
    </select>
    @error('installment_plan_id')<p class="os-error-text">{{ $message }}</p>@enderror

    @if($this->insurance && $this->insurance->is_enabled)
        <label class="mt-4 flex cursor-pointer items-start gap-3">
            <input type="checkbox" name="has_insurance" value="1" wire:model.live="hasInsurance" class="mt-0.5 h-5 w-5 shrink-0 accent-mango">
            <span class="text-sm text-ink">
                Add insurance <span class="font-semibold text-mango-ink">({{ $this->insurance->rate }}% of total)</span>
                <span class="mt-0.5 block text-xs leading-relaxed text-slate">Protect your purchase against damage, loss, or theft during the installment period.</span>
            </span>
        </label>
    @endif

    {{-- ===== LIVE BREAKDOWN ===== --}}
    @if($this->breakdown)
        <div class="mt-4 rounded-xl border border-mango/30 bg-mango/5 p-4 sm:p-5" wire:key="bd-{{ $this->planId }}-{{ $this->hasInsurance ? 1 : 0 }}">
            <dl class="space-y-2.5 text-sm">
                <div class="flex items-center justify-between">
                    <dt class="text-slate">Item total</dt>
                    <dd class="os-price text-ink">₦{{ number_format($this->breakdown['principal'], 2) }}</dd>
                </div>
                @if($this->breakdown['interest'] > 0)
                <div class="flex items-center justify-between">
                    <dt class="text-slate">Interest ({{ $this->breakdown['interest_rate'] }}%)</dt>
                    <dd class="os-price text-ink">+ ₦{{ number_format($this->breakdown['interest'], 2) }}</dd>
                </div>
                @endif
                <div class="flex items-center justify-between">
                    <dt class="text-slate">Shipping</dt>
                    <dd class="os-price text-ink">+ ₦{{ number_format($this->breakdown['shipping_fee'], 2) }}</dd>
                </div>
                @if($this->breakdown['has_insurance'])
                <div class="flex items-center justify-between">
                    <dt class="text-slate">Insurance ({{ $this->insurance->rate }}%)</dt>
                    <dd class="os-price text-ink">+ ₦{{ number_format($this->breakdown['insurance_fee'], 2) }}</dd>
                </div>
                @endif
            </dl>
            <div class="os-hr my-3.5"></div>
            <div class="flex items-center justify-between">
                <span class="font-display text-sm font-bold text-ink">Total to pay back</span>
                <span class="os-price text-lg font-bold text-brand">₦{{ number_format($this->breakdown['grand_total'], 2) }}</span>
            </div>
            <div class="mt-3.5 flex flex-wrap items-baseline gap-2 border-t border-dashed border-ink/15 pt-3.5 text-xs text-slate">
                <span class="os-price text-2xl font-bold text-mango-ink">₦{{ number_format($this->breakdown['per_installment'], 2) }}</span>
                <span>per {{ $this->breakdown['type'] === 'weekly' ? 'week' : 'month' }} × {{ $this->breakdown['duration'] }}</span>
            </div>
        </div>
    @else
        <div class="mt-4 flex items-center gap-2.5 rounded-xl border border-dashed border-ink/15 bg-paper-deep/50 p-4 text-sm text-slate">
            <i class="bi bi-calculator text-mango-deep"></i> Pick a plan above to see your payment breakdown.
        </div>
    @endif
</div>
