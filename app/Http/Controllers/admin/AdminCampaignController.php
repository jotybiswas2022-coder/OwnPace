<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignRequest;
use App\Models\Campaign;
use App\Models\User;
use App\Models\CampaignLog;

class AdminCampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::withCount('logs')->latest()->paginate(20);
        return view('backend.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('backend.campaigns.create');
    }

    public function store(CampaignRequest $request)
    {
        $this->authorize('manage', Campaign::class);

        $status = $request->action === 'send_now' ? 'sent' : 'draft';

        $campaign = Campaign::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,
            'channel' => $request->channel,
            'audience' => $request->audience,
            'status' => $status,
            'sent_at' => $request->action === 'send_now' ? now() : null,
        ]);

        if ($request->action === 'send_now') {
            $this->sendCampaign($campaign);
        }

        $msg = $request->action === 'send_now'
            ? 'Campaign created and sent successfully!'
            : 'Campaign saved as draft!';

        return redirect()->route('admin.campaigns.index')->with('success', $msg);
    }

    public function send(Campaign $campaign)
    {
        $this->authorize('manage', $campaign);

        if ($campaign->status === 'sent') {
            return back()->with('error', 'This campaign has already been sent.');
        }

        $this->sendCampaign($campaign);

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Campaign sent successfully!');
    }

    private function sendCampaign(Campaign $campaign)
    {
        $users = collect();
        switch ($campaign->audience) {
            case 'all':
                $users = User::where('is_active', true)->get();
                break;
            case 'active_users':
                $users = User::where('is_active', true)
                    ->where('is_suspended', false)
                    ->get();
                break;
            case 'overdue_users':
                $users = User::whereHas('orders.installmentPayments', function($q) {
                    $q->where('status', 'overdue');
                })->get();
                break;
        }

        foreach ($users as $user) {
            CampaignLog::create([
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
                'channel' => $campaign->channel === 'both' ? 'email' : $campaign->channel,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            \App\Models\Notification::create([
                'user_id' => $user->id,
                'type' => 'promotion',
                'channel' => $campaign->channel === 'both' ? 'email' : $campaign->channel,
                'title' => $campaign->subject ?? $campaign->name,
                'message' => $campaign->content,
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorize('manage', $campaign);

        $campaign->logs()->delete();
        $campaign->delete();
        return back()->with('success', 'Campaign deleted successfully!');
    }
}
