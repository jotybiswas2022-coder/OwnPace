<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WalletCreditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:cashback,reward,store_credit,adjustment,bonus',
            'description' => 'nullable|string|max:500',
            'withdrawable' => 'boolean',
        ];
    }
}
