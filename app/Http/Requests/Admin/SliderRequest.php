<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slider1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'slider2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ];
    }
}
