<?php

namespace App\Http\Controllers;

use App\Models\CampaignLinkClick;
use App\Models\CampaignLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * CampaignTrackingController — public (no auth) endpoints powering campaign
 * open + click metrics.
 *
 *   GET /c/t/{log}   1px transparent GIF; records the open
 *   GET /c/l/{log}?url=…  records the click then redirects to the URL
 */
class CampaignTrackingController extends Controller
{
    /** 1×1 transparent GIF. */
    protected const PIXEL = 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    public function open(CampaignLog $log)
    {
        if (! $log->opened_at) {
            $log->update(['opened_at' => now()]);
        }

        $log->increment('open_count');

        return response(base64_decode(self::PIXEL), 200, [
            'Content-Type' => 'image/gif',
            'Content-Length' => '35',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function click(Request $request, CampaignLog $log)
    {
        $url = $request->query('url');

        // Only http(s) targets — prevents using the tracker as an open redirect.
        $scheme = is_string($url) ? parse_url($url, PHP_URL_SCHEME) : null;
        $validUrl = is_string($url) && in_array($scheme, ['http', 'https'], true);

        if ($validUrl) {
            // A click is also an open.
            if (! $log->opened_at) {
                $log->update(['opened_at' => now()]);
            }

            $log->increment('click_count');

            CampaignLinkClick::create([
                'campaign_log_id' => $log->id,
                'url' => Str::limit($url, 900),
            ]);

            return redirect()->away($url);
        }

        return redirect('/');
    }
}
