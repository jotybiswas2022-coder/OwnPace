{{-- Branded campaign email — indigo/mango palette, inline styles for client compat. --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->name }}</title>
</head>
<body style="margin:0;padding:0;background:#f6f6f4;-webkit-text-size-adjust:100%;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">{{ $campaign->subject ?? $campaign->name }}</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f6f6f4;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">
                    {{-- Header --}}
                    <tr>
                        <td style="padding:20px 0 16px;text-align:center;">
                            <span style="display:inline-block;background:#f5a623;color:#1a1b23;font-family:Georgia,'Times New Roman',serif;font-size:22px;font-weight:700;padding:8px 18px;border-radius:12px;">{{ storeName() }}</span>
                        </td>
                    </tr>
                    {{-- Body card --}}
                    <tr>
                        <td style="background:#ffffff;border:1px solid rgba(26,27,35,0.08);border-radius:16px;padding:32px 28px;">
                            @if(!empty($campaign->subject))
                            <h1 style="margin:0 0 16px;font-family:Arial,Helvetica,sans-serif;font-size:22px;line-height:1.3;color:#2e2a6b;">{{ $campaign->subject }}</h1>
                            @endif
                            <div style="font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.65;color:#1a1b23;">
                                {!! $content !!}
                            </div>
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 8px 8px;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.6;color:#6b7280;">
                            You received this message because you have an account with {{ storeName() }}.<br>
                            Pay down a balance until it's yours — <strong>own at your own pace.</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {!! $pixel !!}
</body>
</html>
