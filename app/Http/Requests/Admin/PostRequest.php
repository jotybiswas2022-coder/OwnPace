<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // PostController update() resolves the record by a plain route param
        // (not model-bound), so the file is only optional on updates.
        // Detect updates by the presence of any route parameter, so the rule
        // holds regardless of the param's exact name ({id} / {post}).
        $isUpdate = $this->route() !== null && count($this->route()->parameters()) > 0;
        $fileRule = $isUpdate
            ? 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4|max:512000'
            : 'required|file|mimetypes:image/jpeg,image/png,image/gif,image/webp,video/mp4|max:512000';

        return [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'contact_number' => 'required|string|max:20',
            'division' => 'required|string|max:100',
            'status' => 'nullable|in:0,1',
            'file' => $fileRule,
        ];
    }
}
