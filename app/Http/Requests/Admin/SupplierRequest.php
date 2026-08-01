<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'api_endpoint' => 'nullable|url|max:255',
            // Supplier integration fields — kept in validated() so updates
            // don't silently drop previously-saved values.
            'api_key' => 'nullable|string',
            'api_secret' => 'nullable|string',
            'meta_data' => 'nullable|array',
            'is_active' => 'boolean',
        ];
    }
}
