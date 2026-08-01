<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InstallmentPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'type' => 'required|in:weekly,monthly',
            'duration' => 'required|integer|min:1|max:52',
            'duration_days' => 'nullable|integer|min:1',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'late_fee_enabled' => 'boolean',
            'late_fee_percent' => 'nullable|numeric|min:0|max:100',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Derive the total duration in days from the cadence when not supplied.
        if (!$this->filled('duration_days') && $this->filled('type') && $this->filled('duration')) {
            $this->merge([
                'duration_days' => $this->type === 'weekly'
                    ? $this->duration * 7
                    : $this->duration * 30,
            ]);
        }
    }
}
