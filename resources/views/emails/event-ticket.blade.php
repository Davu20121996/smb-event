<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name ?? __('frontend.nav_event') }}</title>
</head>
@php
use Illuminate\Support\Facades\URL;
// Không cần force URL nữa vì dùng token trực tiếp, không dùng signedRoute
@endphp
<body style="margin:0; padding:0; background:#fff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#6c757d; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,.15);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#2563eb; color:#ffffff; text-align:center; padding:32px 24px;">
                            <div style="font-size:36px; line-height:1; margin-bottom:10px;">&#x1F39F;&#xFE0F;</div>
                            <h1 style="margin:0; font-size:22px; font-weight:800;">Your Event Ticket</h1>
                            <p style="margin:6px 0 0; opacity:.85;">{{ $event->name ?? __('frontend.nav_event') }}</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;">
                                {{ __('mail.ticket_greeting', ['name' => $attendee->name]) }}
                            </p>
                            <p style="margin:0 0 24px; line-height:1.6; color:#444;">
                                {{ __('mail.ticket_intro', ['event' => $event->name ?? __('frontend.nav_event')]) }}
                            </p>

                            <!-- Ticket Card -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:2px solid #2563eb; border-radius:8px; margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td width="65%" valign="top" style="padding-right:12px; vertical-align:top;">
                                                    <h2 style="margin:0 0 12px; font-size:17px; color:#2563eb;">{{ $event->name ?? __('frontend.nav_event') }}</h2>
                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px;">
                                                        <tr>
                                                            <td style="color:#6b7280; padding:3px 0; width:70px;">Name:</td>
                                                            <td style="padding:3px 0; font-weight:600;">{{ $attendee->name }}</td>
                                                        </tr>
                                                        @if($attendee->company)
                                                        <tr>
                                                            <td style="color:#6b7280; padding:3px 0; width:70px;">Company:</td>
                                                            <td style="padding:3px 0; font-weight:600;">{{ $attendee->company }}</td>
                                                        </tr>
                                                        @endif
                                                        @if ($event && $event->start_date)
                                                        <tr>
                                                            <td style="color:#6b7280; padding:3px 0; width:70px;">Date:</td>
                                                            <td style="padding:3px 0; font-weight:600;">{{ \Carbon\Carbon::parse($event->start_date)->format('F d, Y') }}</td>
                                                        </tr>
                                                        @endif
                                                        @if ($event && $event->venue)
                                                        <tr>
                                                            <td style="color:#6b7280; padding:3px 0; width:70px;">Venue:</td>
                                                            <td style="padding:3px 0; font-weight:600;">{{ $event->venue }}</td>
                                                        </tr>
                                                        @endif
                                                        @if ($attendee->ticket_type)
                                                        <tr>
                                                            <td style="color:#6b7280; padding:3px 0; width:70px;">Ticket:</td>
                                                            <td style="padding:3px 0; font-weight:600;">{{ $attendee->ticket_type }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td style="color:#6b7280; padding:3px 0; width:70px;">Ticket #:</td>
                                                            <td style="padding:3px 0; font-weight:600; font-family:monospace;">{{ $attendee->qr }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td width="35%" valign="middle" align="center" style="vertical-align:middle;">
                                                    <img src="{{ $qrUrl }}" alt="QR" width="140" height="140" style="width:140px; height:140px; display:block; background:#fff; border:1px solid #e5e5e5; border-radius:6px;">
                                                    <p style="margin:6px 0 0; font-size:12px; color:#666;">Scan to check-in</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-top:2px dashed #cbd5e1; text-align:center; padding:10px; background:#f8fafc;">
                                        <small style="color:#6b7280;">Ticket ID: <strong style="font-family:monospace; color:#171717;">{{ $attendee->qr }}</strong></small>
                                    </td>
                                </tr>
                            </table>

                            <!-- Confirm Attendance Button -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $attendee->confirmation_url }}"
                                           style="display:inline-block; background:#16a34a; color:#ffffff; text-decoration:none; font-size:16px; font-weight:700; padding:14px 36px; border-radius:8px; letter-spacing:0.3px;">
                                            ✅ Xác nhận tham dự sự kiện
                                        </a>
                                        <p style="margin:10px 0 0; font-size:12px; color:#6b7280;">
                                            Vui lòng xác nhận để chúng tôi chuẩn bị tốt nhất cho bạn.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Instructions -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e5e5; border-radius:8px; background:#f8fafc; padding:16px 18px; margin-bottom:24px;">
                                <tr>
                                    <td style="font-size:14px; color:#171717;">
                                        <p style="margin:0 0 8px; font-weight:700; color:#2563eb;">&#x2139;&#xFE0F; Check-in Instructions</p>
                                        <ul style="margin:0; padding-left:18px; color:#444;">
                                            <li>Show this QR code at the registration desk</li>
                                            <li>You can also show it from your phone screen</li>
                                            <li>Please arrive 15 minutes before the event starts</li>
                                            <li>Bring a valid ID for verification</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 8px; font-size:15px; color:#999;">{{ __('mail.ticket_note') }}</p>

                            <p style="margin:20px 0 0; color:#666;">
                                {{ __('mail.footer') }}<br>
                                <strong style="color:#171717;">{{ __('mail.footer_team') }}</strong>
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