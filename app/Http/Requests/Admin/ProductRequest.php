<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'price' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => ['nullable', 'string', Rule::unique('products', 'sku')->ignore($this->route('product')?->id)],
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'shipping_fee' => 'nullable|numeric|min:0',
            'insurance_fee' => 'nullable|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'specifications' => 'nullable|array',
            'status' => 'nullable|in:active,inactive,draft',
        ];
    }
}
