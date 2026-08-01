<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'phone' => 'required|string',
            'location' => 'required|string',
            'default_gateway' => 'nullable|string',
            'paystack_public' => 'nullable|string',
            'paystack_secret' => 'nullable|string',
            'flutterwave_public' => 'nullable|string',
            'flutterwave_secret' => 'nullable|string',
            'flutterwave_encryption' => 'nullable|string',
            'korapay_public' => 'nullable|string',
            'korapay_secret' => 'nullable|string',
        ];
    }
}
