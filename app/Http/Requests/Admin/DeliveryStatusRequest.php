<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'delivery_status' => 'required|in:pending,eligible,processing,shipped,in_transit,out_for_delivery,delivered,failed',
        ];
    }
}
