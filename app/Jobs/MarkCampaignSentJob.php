<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * MarkCampaignSentJob — finalizes a campaign once every recipient log has
 * settled (sent / failed). If later chains are still processing it re-checks
 * itself in five minutes instead of prematurely closing the campaign.
 */
class MarkCampaignSentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 15;

    public function __construct(public Campaign $campaign)
    {
    }

    public function handle(): void
    {
        $pending = $this->campaign->logs()->where('status', 'pending')->count();

        if ($pending > 0) {
            // Later chains may still be sending — check back shortly.
            $this->release(300);

            return;
        }

        $failed = $this->campaign->logs()->where('status', 'failed')->count();
        $total = $this->campaign->logs()->count();

        $status = $total > 0 && $failed === $total
            ? 'failed'
            : ($failed > 0 ? 'partial' : 'sent');

        $this->campaign->update([
            'status' => $status,
            'sent_at' => now(),
        ]);
    }

    /**
     * If the re-check loop ever exhausts its attempts, close the campaign out
     * instead of leaving it stuck in "sending".
     */
    public function failed(\Throwable $e): void
    {
        $this->campaign->update([
            'status' => $this->campaign->logs()->where('status', 'failed')->exists() ? 'partial' : 'failed',
        ]);
    }
}
