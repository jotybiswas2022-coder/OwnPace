<?php

namespace App\Livewire;

use App\Models\InstallmentPlan;
use App\Models\InsuranceSetting;
use App\Services\InstallmentCalculatorService;
use App\Services\MoneyService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * CheckoutBreakdown — the live "price + interest + shipping + insurance =
 * total, total ÷ installments = per payment" panel on the checkout page.
 *
 * The rendered <select> and checkbox carry real name attributes so the
 * enclosing checkout form still submits them; wire:model keeps the breakdown
 * in sync as the customer picks a plan and toggles insurance.
 */
class CheckoutBreakdown extends Component
{
    /** Cart subtotal after discount (₦). */
    public float $subtotal = 0;

    /** Shipping fee (₦) resolved from ProductFee. */
    public float $shippingFee = 0;

    public int $planId = 0;

    public bool $hasInsurance = false;

    public function mount(float $subtotal = 0, float $shippingFee = 0): void
    {
        $this->subtotal = $subtotal;
        $this->shippingFee = $shippingFee;
    }

    /**
     * Notify the checkout page which plan is selected so it can show the
     * plan-scoped terms link before the customer ticks "I agree".
     */
    public function updatedPlanId(): void
    {
        $this->dispatch('checkout-plan-changed', planId: $this->planId);
    }

    #[Computed]
    public function plans()
    {
        return InstallmentPlan::where('is_active', true)->orderBy('sort_order')->get();
    }

    #[Computed]
    public function insurance()
    {
        return InsuranceSetting::first();
    }

    #[Computed]
    public function breakdown(): ?array
    {
        $plan = $this->planId
            ? InstallmentPlan::where('is_active', true)->find($this->planId)
            : null;

        if (!$plan) {
            return null;
        }

        $insuranceRate = $this->hasInsurance && $this->insurance?->is_enabled
            ? (float) $this->insurance->rate
            : null;

        return InstallmentCalculatorService::breakdown(
            $this->subtotal,
            $plan,
            $this->shippingFee,
            $insuranceRate,
            0
        );
    }

    public function render()
    {
        return view('livewire.checkout-breakdown');
    }
}
