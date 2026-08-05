<?php

namespace App\Jobs;

use App\Models\CampaignLog;
use App\Services\Campaigns\CampaignMailBuilder;
use App\Services\Messaging\MailerFactory;
use App\Services\Messaging\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * SendCampaignMessageJob — delivers one recipient's copy of a campaign over
 * the channels the campaign targets (email, SMS, or both). Each failure is
 * recorded on the log row so the metrics view can explain partial sends.
 */
class SendCampaignMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public CampaignLog $log)
    {
    }

    public function handle(): void
    {
        $campaign = $this->log->campaign;
        $user = $this->log->user;

        if (! $campaign) {
            $this->failLog('Campaign was deleted while this message was queued.');

            return;
        }

        if (! $user) {
            $this->failLog('Recipient account no longer exists.');

            return;
        }

        $errors = [];
        $providerMessageId = null;

        $wantsEmail = in_array($this->log->channel, ['email', 'both'], true);
        $wantsSms = in_array($this->log->channel, ['sms', 'both'], true);

        if ($wantsEmail) {
            if (empty($user->email)) {
                $errors[] = 'No email on file';
            } else {
                $html = CampaignMailBuilder::html($campaign, $this->log, $user->name ?? '');

                if (! MailerFactory::send($user->email, $campaign->subject ?: $campaign->name, $html)) {
                    $errors[] = 'Email send failed';
                }
            }
        }

        if ($wantsSms) {
            if (empty($user->phone)) {
                $errors[] = 'No phone on file';
            } else {
                [$ok, $messageId] = SmsService::send($user->phone, CampaignMailBuilder::sms($campaign));

                if ($ok) {
                    $providerMessageId = $messageId;
                } else {
                    $errors[] = 'SMS send failed';
                }
            }
        }

        $totalAttempts = ($wantsEmail ? 1 : 0) + ($wantsSms ? 1 : 0);
        $status = count($errors) >= $totalAttempts && $totalAttempts > 0 ? 'failed' : 'sent';

        $this->log->update([
            'status' => $status,
            'sent_at' => now(),
            'provider_message_id' => $providerMessageId,
            'error' => $errors !== [] ? implode('; ', $errors) : null,
        ]);
    }

    public function failed(\Throwable $e): void
    {
        $this->failLog('Queued job failed: '.substr($e->getMessage(), 0, 200));
    }

    protected function failLog(string $reason): void
    {
        $this->log->update([
            'status' => 'failed',
            'sent_at' => now(),
            'error' => $reason,
        ]);
    }
}
