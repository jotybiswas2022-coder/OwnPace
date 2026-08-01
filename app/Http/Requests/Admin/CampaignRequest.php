<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'channel' => 'required|in:email,sms,both',
            'audience' => 'required|in:all,active_users,overdue_users,specific',
            'action' => 'required|in:draft,send_now',
        ];
    }
}
