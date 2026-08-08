<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name ?? __('frontend.nav_event') }}</title>
</head>
<body style="margin:0; padding:0; background:#fff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#6c757d; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.15);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#2563eb; color:#ffffff; text-align:center; padding:32px 24px;">
                            <div style="font-size:36px; line-height:1; margin-bottom:10px;">&#x1F4C5;</div>
                            <h1 style="margin:0; font-size:22px; font-weight:800;">{{ $event->name ?? __('frontend.nav_event') }}</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:32px;">
                            <h2 style="margin:0 0 16px; font-size:20px; font-weight:800; color:#171717;">Verify Your Email Address</h2>

                            <p style="margin:0 0 16px;">
                                {{ __('mail.greeting', ['name' => $attendee->name]) }}
                            </p>
                            <p style="margin:0 0 24px; line-height:1.6; color:#444;">
                                {{ __('mail.thanks') }}
                            </p>

                            <!-- Verify button -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:28px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $confirmUrl }}" style="display:inline-block; background:#2563eb; color:#ffffff; padding:14px 48px; border-radius:8px; font-size:16px; font-weight:700; text-decoration:none;">
                                            &#x2705; Verify My Email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 6px; font-size:13px; color:#6b7280;">Or copy and paste this link into your browser:</p>
                            <p style="margin:0 0 24px; font-size:13px; background:#f1f5f9; border-radius:6px; padding:10px 12px; color:#374151; word-break:break-all;">{{ $confirmUrl }}</p>

                            <!-- Registration Details -->
                            <h3 style="margin:0 0 12px; font-size:15px; font-weight:800; color:#171717; border-top:1px solid #e5e5e5; padding-top:20px;">Your Registration Details:</h3>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                                <tr>
                                    <td style="color:#6b7280; padding:5px 0; width:120px;">Name:</td>
                                    <td style="padding:5px 0; font-weight:600;">{{ $attendee->name }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280; padding:5px 0; width:120px;">Email:</td>
                                    <td style="padding:5px 0; font-weight:600;">{{ $attendee->email }}</td>
                                </tr>
                                @if($attendee->company)
                                <tr>
                                    <td style="color:#6b7280; padding:5px 0; width:120px;">Company:</td>
                                    <td style="padding:5px 0; font-weight:600;">{{ $attendee->company }}</td>
                                </tr>
                                @endif
                                @if($attendee->phone)
                                <tr>
                                    <td style="color:#6b7280; padding:5px 0; width:120px;">Phone:</td>
                                    <td style="padding:5px 0; font-weight:600;">{{ $attendee->phone }}</td>
                                </tr>
                                @endif
                                @if($attendee->company_size_label)
                                <tr>
                                    <td style="color:#6b7280; padding:5px 0; width:120px;">Company size:</td>
                                    <td style="padding:5px 0; font-weight:600;">{{ $attendee->company_size_label }}</td>
                                </tr>
                                @endif
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0 0; background:#fff7e6; border:1px solid #ffe4b5; border-radius:8px; padding:12px 14px;">
                                <tr>
                                    <td style="font-size:13px; color:#7a4a00;">
                                        &#x23F0; This link will expire in <strong>24 hours</strong>. If you did not register for this event, please ignore this email.
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0; line-height:1.5; color:#6b7280;">
                                {{ __('mail.note') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc; text-align:center; padding:20px 24px; border-top:1px solid #e5e5e5;">
                            <p style="margin:0 0 6px; font-size:12px; color:#6b7280;">
                                SMB Events &bull; {{ $event->venue ?? '' }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>