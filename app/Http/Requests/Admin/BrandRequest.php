<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    /**
     * Authorization is handled by the AdminMiddleware (admin role) on the
     * route group; policies handle per-resource checks.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
        ];
    }
}
