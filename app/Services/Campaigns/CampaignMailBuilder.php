<?php

namespace App\Services\Campaigns;

use App\Models\Campaign;
use App\Models\CampaignLog;

/**
 * CampaignMailBuilder — turns a campaign's plain-text content into the HTML
 * email sent to one recipient:
 *   - every URL is rewritten through the click-tracking endpoint
 *   - a 1px tracking pixel reports email opens
 *   - the layout uses the brand palette (indigo/mango)
 */
class CampaignMailBuilder
{
    public static function html(Campaign $campaign, CampaignLog $log, string $customerName = ''): string
    {
        $content = self::body($campaign, $log, $customerName);

        $pixel = '<img src="'.url('/c/t/'.$log->id).'" alt="" width="1" height="1" style="display:block;width:1px;height:1px;border:0;" />';

        return view('emails.campaign', [
            'campaign' => $campaign,
            'content' => $content,
            'pixel' => $pixel,
        ])->render();
    }

    /**
     * Escape + linkify the message, wrapping every link through the tracker.
     */
    protected static function body(Campaign $campaign, CampaignLog $log, string $customerName): string
    {
        $content = $customerName !== ''
            ? str_replace(['{customer_name}', '{name}'], $customerName, (string) $campaign->content)
            : (string) $campaign->content;

        $content = nl2br(e($content));

        return (string) preg_replace_callback(
            '#(https?://[^\s<>"\']+)#i',
            function (array $match) use ($log) {
                $url = $match[1];
                $track = url('/c/l/'.$log->id).'?url='.urlencode($url);

                return '<a href="'.$track.'" style="color:#d98c0f;font-weight:600;text-decoration:underline;">'.$url.'</a>';
            },
            $content
        );
    }

    /**
     * Short SMS form of a campaign: subject line, then content trimmed to a
     * single SMS (160 chars).
     */
    public static function sms(Campaign $campaign): string
    {
        $head = trim((string) ($campaign->subject ?: $campaign->name));

        $text = str_replace(["\r", "\n"], ' ', strip_tags((string) $campaign->content));
        $text = preg_replace('/\s+/', ' ', $text) ?? '';
        $text = trim($text);

        $body = $head !== '' ? $head.' — '.$text : $text;

        return mb_substr($body, 0, 158);
    }
}
