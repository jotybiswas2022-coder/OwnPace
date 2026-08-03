<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TermsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'installment_plan_id' => 'nullable|integer|exists:installment_plans,id',
        ];
    }
}
