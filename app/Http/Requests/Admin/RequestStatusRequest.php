<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shared validation for approving/rejecting Product and Exchange requests.
 * Both flows carry a status + optional admin note.
 */
class RequestStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:submitted,under_review,pending,approved,rejected,completed',
            'admin_notes' => 'nullable|string',
        ];
    }
}
