<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher ưu đãi</title>
</head>
<body style="margin:0; padding:0; background:#fff; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f7; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #e5e5e5;">
                    <tr>
                        <td style="background:#0f172a; color:#ffffff; padding:28px 32px;">
                            <h1 style="margin:0; font-size:20px; font-weight:700; line-height:1.3;">
                                🎁 Bạn nhận được voucher ưu đãi
                            </h1>
                            <p style="margin:6px 0 0; font-size:14px; color:#cbd5e1;">
                                {{ $event->name ?? '' }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;">
                                Kính gửi <strong>{{ $attendee->name }}</strong>,
                            </p>
                            <p style="margin:0 0 24px; line-height:1.5;">
                                Ban tổ chức đã gửi tặng bạn một voucher ưu đãi cho sự kiện. Nhập mã bên dưới vào ô
                                <strong>"Mã giảm giá / Voucher"</strong> khi đăng ký sự kiện để nhận ưu đãi.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f0fdf4; border:2px dashed #16a34a; border-radius:8px; padding:24px; text-align:center; margin:0 0 24px;">
                                <tr>
                                    <td align="center">
                                        <p style="margin:0 0 8px; font-size:13px; color:#166534; text-transform:uppercase; letter-spacing:1px;">Mã voucher của bạn</p>
                                        <p style="margin:0; font-size:28px; font-weight:800; color:#166534; letter-spacing:3px; user-select:all;">{{ $voucher->code }}</p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #e5e5e5; font-size:14px;">
                                <tr>
                                    <td style="padding:12px 8px; border-bottom:1px solid #e5e5e5; color:#666;">Ưu đãi</td>
                                    <td style="padding:12px 8px; border-bottom:1px solid #e5e5e5; font-weight:600;">{{ $voucher->discount_label }}</td>
                                </tr>
                                @if($voucher->description)
                                <tr>
                                    <td style="padding:12px 8px; border-bottom:1px solid #e5e5e5; color:#666;">Mô tả</td>
                                    <td style="padding:12px 8px; border-bottom:1px solid #e5e5e5; font-weight:600;">{{ $voucher->description }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:12px 8px; border-bottom:1px solid #e5e5e5; color:#666;">Hạn sử dụng</td>
                                    <td style="padding:12px 8px; border-bottom:1px solid #e5e5e5; font-weight:600;">{{ $voucher->valid_until ? $voucher->valid_until->format('d/m/Y H:i') : 'Không giới hạn' }}</td>
                                </tr>
                                @if($note)
                                <tr>
                                    <td style="padding:12px 8px; border-bottom:1px solid #e5e5e5; color:#666;">Ghi chú</td>
                                    <td style="padding:12px 8px; border-bottom:1px solid #e5e5e5; font-weight:600;">{{ $note }}</td>
                                </tr>
                                @endif
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:28px 0 0; text-align:center;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ rtrim(config('app.url'), '/') . route('event', [], false) }}" style="display:inline-block; background:#2563eb; color:#ffffff; padding:14px 32px; border-radius:8px; font-size:15px; font-weight:600; text-decoration:none;">
                                            Đăng ký ngay →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0; line-height:1.5; color:#999999;">
                                Nếu bạn không đăng ký được sự kiện, vui lòng liên hệ ban tổ chức để được hỗ trợ.
                            </p>

                            <p style="margin:32px 0 0;">
                                Trân trọng,<br>
                                <strong>Ban tổ chức sự kiện</strong>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>