<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\Setting;
use App\Models\InsuranceSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $insurance = InsuranceSetting::first();
        return view('backend.settings', compact('settings', 'insurance'));
    }

    public function update(SettingsRequest $request)
    {
        $this->authorize('manage', Setting::class);

        $settings = Setting::first() ?? new Setting();

        $settings->email = $request->email;
        $settings->phone = $request->phone;
        $settings->location = $request->location;
        $settings->default_gateway = $request->default_gateway;

        // NOTE: gateway API keys are NOT managed here anymore — they live in
        // the Secure Configuration screen (Super Admin only, encrypted).
        // Leave gateway_config untouched so saving general settings never
        // wipes the stored credentials.

        // Wallet rules.
        $settings->allow_topup_withdrawal = $request->boolean('allow_topup_withdrawal');
        $settings->withdrawal_fee_percent = $request->withdrawal_fee_percent ?? 10;
        $settings->topup_bonus_percent = $request->topup_bonus_percent ?? 0;

        $settings->save();

        // Insurance is storewide: enable/disable + rate, read at checkout.
        $insurance = InsuranceSetting::first() ?? new InsuranceSetting();
        $insurance->name = 'Insurance Fee';
        $insurance->rate = $request->insurance_rate ?? $insurance->rate ?? 10;
        $insurance->type = 'percentage';
        $insurance->is_enabled = $request->boolean('insurance_enabled');
        $insurance->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
