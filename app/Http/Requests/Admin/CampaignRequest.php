<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // policy enforced in the controller
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:190'],
            'subject' => ['nullable', 'string', 'max:190'],
            'content' => ['required', 'string'],
            'channel' => ['required', 'in:email,sms,both'],
            'audience' => ['required', 'in:all,active_users,overdue_users,plan_users,repeat_customers'],
            'plan_id' => ['nullable', 'integer', 'exists:installment_plans,id'],
            'min_orders' => ['nullable', 'integer', 'min:1', 'max:999'],
            'action' => ['required', 'in:draft,send_now,schedule'],
            'scheduled_at' => ['nullable', 'required_if:action,schedule', 'date', 'after:now'],
            'template_id' => ['nullable', 'integer', 'exists:campaign_templates,id'],
            'save_template' => ['nullable'],
            'template_name' => ['nullable', 'string', 'max:190'],
        ];
    }
}
