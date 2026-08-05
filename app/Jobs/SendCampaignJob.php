<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Models\User;
use App\Services\Campaigns\CampaignSegmentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

/**
 * SendCampaignJob — the broadcast orchestrator.
 *
 * 1. Resolves the campaign's segment into concrete customer IDs.
 * 2. Snapshots one CampaignLog per recipient (contact + channel) so metrics
 *    survive account edits and the send is auditable.
 * 3. Dispatches SendCampaignMessageJob per recipient in bounded chains (500
 *    per chain) so large audiences are sent in batches without one monster
 *    job. The final chain is capped by MarkCampaignSentJob, which closes the
 *    campaign's status.
 *
 * Scheduled campaigns reach this same job via a delayed dispatch, so "send
 * now" and "send at a time" share one code path.
 */
class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Chain size — per-recipient jobs per dispatched batch. */
    protected const BATCH = 500;

    public function __construct(public Campaign $campaign)
    {
    }

    public function handle(): void
    {
        // Never re-run a campaign that is already sending/sent — a queue retry
        // must not duplicate the recipient logs.
        if (in_array($this->campaign->status, ['sending', 'sent', 'failed', 'partial'], true)) {
            return;
        }

        $this->campaign->update(['status' => 'sending']);

        $userIds = CampaignSegmentService::resolveIds($this->campaign);
        $logs = [];

        // Snapshot recipient logs in chunks to bound memory on big audiences.
        foreach ($userIds->chunk(self::BATCH) as $idChunk) {
            $users = User::whereIn('id', $idChunk)->get(['id', 'email', 'phone']);

            foreach ($users as $user) {
                $logs[] = CampaignLog::create([
                    'campaign_id' => $this->campaign->id,
                    'user_id' => $user->id,
                    'channel' => $this->campaign->channel,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'status' => 'pending',
                ]);
            }
        }

        if ($logs === []) {
            $this->campaign->update(['status' => 'sent', 'sent_at' => now()]);

            return;
        }

        // Dispatch in bounded chains; only the last chain carries the finalizer.
        $chains = array_chunk($logs, self::BATCH);
        $lastIndex = count($chains) - 1;

        foreach ($chains as $index => $batch) {
            $jobs = array_map(fn (CampaignLog $log) => new SendCampaignMessageJob($log), $batch);

            if ($index === $lastIndex) {
                $jobs[] = new MarkCampaignSentJob($this->campaign);
            }

            Bus::chain($jobs)->dispatch();
        }
    }
}
