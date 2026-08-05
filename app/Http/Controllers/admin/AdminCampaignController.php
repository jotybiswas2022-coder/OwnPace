<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignRequest;
use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Models\CampaignTemplate;
use App\Models\InstallmentPlan;
use App\Services\Campaigns\CampaignSegmentService;
use Illuminate\Http\Request;

class AdminCampaignController extends Controller
{
    public function index()
    {
        $this->authorize('manage', Campaign::class);

        $campaigns = Campaign::withCount('logs')->latest()->paginate(15);

        // Per-campaign delivery metrics from three grouped queries — the
        // model accessors are for single-model use; listing must not N+1.
        $ids = $campaigns->pluck('id');

        $statusCounts = CampaignLog::whereIn('campaign_id', $ids)
            ->selectRaw('campaign_id, status, COUNT(*) as c, SUM(click_count) as clicks')
            ->groupBy('campaign_id', 'status')
            ->get()
            ->groupBy('campaign_id');

        $opens = CampaignLog::whereIn('campaign_id', $ids)
            ->whereNotNull('opened_at')
            ->selectRaw('campaign_id, COUNT(*) as c')
            ->groupBy('campaign_id')
            ->pluck('c', 'campaign_id');

        $metrics = [];
        foreach ($campaigns as $campaign) {
            $byStatus = $statusCounts->get($campaign->id, collect());
            $delivered = (int) $byStatus->whereIn('status', ['sent', 'delivered'])->sum('c');
            $opened = (int) ($opens[$campaign->id] ?? 0);
            $clicked = (int) $byStatus->sum('clicks');

            $metrics[$campaign->id] = [
                'delivered' => $delivered,
                'failed' => (int) $byStatus->where('status', 'failed')->sum('c'),
                'opened' => $opened,
                'clicked' => $clicked,
                'open_rate' => $delivered > 0 ? round($opened / $delivered * 100, 1) : 0,
                'click_rate' => $delivered > 0 ? round($clicked / $delivered * 100, 1) : 0,
            ];
        }

        // Platform totals from true aggregates.
        $sentTotal = CampaignLog::whereIn('status', ['sent', 'delivered'])->count();
        $openedTotal = CampaignLog::whereNotNull('opened_at')->count();
        $clickedTotal = (int) CampaignLog::sum('click_count');

        $totals = [
            'campaigns' => Campaign::count(),
            'recipients' => (int) CampaignLog::count(),
            'open_rate' => $sentTotal > 0 ? round($openedTotal / $sentTotal * 100, 1) : 0,
            'click_rate' => $sentTotal > 0 ? round($clickedTotal / $sentTotal * 100, 1) : 0,
        ];

        return view('backend.campaigns.index', compact('campaigns', 'metrics', 'totals'));
    }

    public function create()
    {
        $this->authorize('manage', Campaign::class);

        $templates = CampaignTemplate::latest()->get();
        $plans = InstallmentPlan::where('is_active', true)->orderBy('name')->get();
        $segmentCounts = CampaignSegmentService::countBySegment();

        return view('backend.campaigns.create', compact('templates', 'plans', 'segmentCounts'));
    }

    public function store(CampaignRequest $request)
    {
        $this->authorize('manage', Campaign::class);

        $filters = array_filter([
            'plan_id' => $request->input('plan_id') ? (int) $request->input('plan_id') : null,
            'min_orders' => $request->input('min_orders') ? (int) $request->input('min_orders') : null,
        ]);

        $scheduledAt = $request->action === 'schedule' ? $request->date('scheduled_at') : null;
        $status = $request->action === 'send_now' ? 'sending' : ($scheduledAt ? 'scheduled' : 'draft');

        $campaign = Campaign::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,
            'channel' => $request->channel,
            'audience' => $request->audience,
            'recipient_filters' => $filters !== [] ? $filters : null,
            'template_id' => $request->input('template_id') ?: null,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
        ]);

        // Save as a reusable template while composing.
        if ($request->boolean('save_template') && trim((string) $request->template_name) !== '') {
            CampaignTemplate::create([
                'name' => $request->template_name,
                'subject' => $request->subject,
                'content' => $request->content,
            ]);
        }

        if ($request->action === 'send_now') {
            SendCampaignJob::dispatch($campaign);
        } elseif ($scheduledAt) {
            // Delayed dispatch — the queue releases the job at scheduled_at.
            SendCampaignJob::dispatch($campaign)->delay($scheduledAt);
        }

        $msg = match ($status) {
            'sending' => 'Campaign queued — sending to recipients now.',
            'scheduled' => 'Campaign scheduled for '.$scheduledAt->format('M j, Y g:i A').'.',
            default => 'Campaign saved as draft.',
        };

        return redirect()->route('admin.campaigns.index')->with('success', $msg);
    }

    /**
     * Metrics detail: delivery / open / click stats + recipient log.
     */
    public function show(Request $request, Campaign $campaign)
    {
        $this->authorize('manage', $campaign);

        $query = $campaign->logs()->with('user')->latest('id');

        if ($request->status && in_array($request->status, ['pending', 'sent', 'failed', 'partial'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        $metrics = [
            'recipients' => $campaign->logs()->count(),
            'delivered' => $campaign->delivered_count,
            'failed' => $campaign->failed_count,
            'opened' => $campaign->opened_count,
            'clicked' => $campaign->clicked_count,
            'open_rate' => $campaign->open_rate,
            'click_rate' => $campaign->click_rate,
        ];

        // Top clicked links — one grouped query, no per-log loading.
        $clicks = \App\Models\CampaignLinkClick::whereIn('campaign_log_id', $campaign->logs()->select('id'))
            ->selectRaw('url, COUNT(*) as clicks')
            ->groupBy('url')
            ->orderByDesc('clicks')
            ->limit(10)
            ->get()
            ->map(fn ($row) => ['url' => $row->url, 'clicks' => (int) $row->clicks]);

        // 14-day delivery/open series for the sparkline chart.
        $series = $this->dailySeries($campaign, 14);

        return view('backend.campaigns.show', compact('campaign', 'logs', 'metrics', 'clicks', 'series'));
    }

    /**
     * Manually send a draft or scheduled campaign right now.
     */
    public function send(Campaign $campaign)
    {
        $this->authorize('manage', $campaign);

        if (in_array($campaign->status, ['sent', 'failed', 'partial', 'sending'], true)) {
            return back()->with('error', 'This campaign has already been sent.');
        }

        SendCampaignJob::dispatch($campaign);

        return back()->with('success', 'Campaign queued for sending.');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorize('manage', $campaign);

        $campaign->logs()->delete();
        $campaign->delete();

        return back()->with('success', 'Campaign deleted.');
    }

    // ===== TEMPLATES =====

    public function storeTemplate(Request $request)
    {
        $this->authorize('manage', Campaign::class);

        $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'subject' => ['nullable', 'string', 'max:190'],
            'content' => ['required', 'string'],
        ]);

        CampaignTemplate::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Template saved.');
    }

    public function destroyTemplate(CampaignTemplate $template)
    {
        $this->authorize('manage', Campaign::class);

        $template->delete();

        return back()->with('success', 'Template deleted.');
    }

    // ===== EXPORT =====

    /**
     * CSV of the recipient log (status / open / click per customer).
     */
    public function exportLogs(Campaign $campaign)
    {
        $this->authorize('manage', $campaign);

        $rows = $campaign->logs()->with('user')->get();

        $csv = "Customer,Email,Phone,Channel,Status,Sent At,Opened At,Opens,Clicks,Error\n";
        foreach ($rows as $log) {
            $csv .= implode(',', [
                '"'.($log->user?->name ?? '').'"',
                '"'.$log->email.'"',
                '"'.$log->phone.'"',
                $log->channel,
                $log->status,
                $log->sent_at?->format('Y-m-d H:i:s') ?? '',
                $log->opened_at?->format('Y-m-d H:i:s') ?? '',
                $log->open_count,
                $log->click_count,
                '"'.($log->error ?? '').'"',
            ])."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="campaign_'.$campaign->id.'_logs_'.now()->format('Y-m-d').'.csv"',
        ]);
    }

    // ===== HELPERS =====

    /**
     * Per-day sent vs opened counts over the last N days, for charts.
     *
     * @return array{labels: array<int, string>, sent: array<int, int>, opened: array<int, int>}
     */
    protected function dailySeries(Campaign $campaign, int $days): array
    {
        $from = now()->subDays($days - 1)->startOfDay();

        $sentByDay = $campaign->logs()
            ->whereNotNull('sent_at')
            ->where('sent_at', '>=', $from)
            ->selectRaw('DATE(sent_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $openedByDay = $campaign->logs()
            ->whereNotNull('opened_at')
            ->where('opened_at', '>=', $from)
            ->selectRaw('DATE(opened_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $sent = [];
        $opened = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $day = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('M j');
            $sent[] = (int) ($sentByDay[$day] ?? 0);
            $opened[] = (int) ($openedByDay[$day] ?? 0);
        }

        return compact('labels', 'sent', 'opened');
    }
}
