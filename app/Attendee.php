<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Attendee extends Model
{
    use SoftDeletes;

    public $table = 'attendees';

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'company',
        'tax_code',
        'company_size',
        'interested_products',
        'ticket_type',
        'qr',
        'confirmation_token',
        'status',
        'notes',
        'checked_in_at',
        'confirmed_at',
        'checked_in_by',
        'voucher_id',
        'voucher_code',
        'discount_amount',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function booted()
    {
        static::creating(function (Attendee $attendee) {
            if (empty($attendee->qr)) {
                $attendee->qr = self::generateQrCode();
            }
        });
    }

    protected $casts = [
        'event_id'        => 'integer',
        'checked_in_at'   => 'datetime',
        'confirmed_at'    => 'datetime',
        'checked_in_by'   => 'integer',
        'voucher_id'      => 'integer',
        'discount_amount' => 'float',
    ];

    public const STATUS_LABELS = [
        'pending'        => 'Chờ duyệt',
        'confirmed'      => 'Đã xác nhận',
        'rsvp_confirmed' => 'Đã xác nhận tham dự',
        'attended'       => 'Đã tham gia',
        'cancelled'      => 'Đã hủy',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function checkedInByUser()
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function voucherAssignment()
    {
        return $this->hasOne(VoucherAttendee::class, 'attendee_id')
            ->latestOfMany('assigned_at')
            ->where('status', '!=', 'revoked');
    }

    public function voucherAssignments()
    {
        return $this->hasMany(VoucherAttendee::class, 'attendee_id');
    }

    public function getCheckinByWhomAttribute()
    {
        return $this->checkedInByUser ? $this->checkedInByUser->name : null;
    }

    public function getCompanySizeLabelAttribute()
    {
        $sizes = [
            'lt50'         => 'Dưới 50',
            '50-100'       => '50 - 100',
            '100-200'      => '100 - 200',
            'gt200'        => 'Trên 200',
            'organization' => 'Tổ chức khác',
            // legacy values (đăng ký cũ trước khi đổi options)
            'lt30'         => 'Dưới 30 nhân viên',
            '30-50'        => '30 - 50 nhân viên',
            '50-200'       => '50 - 200 nhân viên',
        ];

        return $sizes[$this->company_size] ?? $this->company_size;
    }

    public static function generateQrCode()
    {
        do {
            $code = 'ATT-' . date('Y') . '-' . strtoupper(Str::random(8));
        } while (static::where('qr', $code)->exists());

        return $code;
    }

    /**
     * Sinh token xác nhận tham dự, lưu vào DB và trả về URL.
     * URL dạng: /event/confirm-attendance/{token} — không cần signed middleware.
     */
    public function generateConfirmationToken(): string
    {
        $token = Str::random(48);

        $this->update(['confirmation_token' => $token]);

        return $token;
    }

    public function getConfirmationUrlAttribute(): string
    {
        $token = $this->confirmation_token ?: $this->generateConfirmationToken();

        return config('app.url') . '/event/confirm-attendance/' . $token;
    }

    public function getVerificationUrlAttribute(): string
    {
        $token = $this->confirmation_token ?: $this->generateConfirmationToken();

        return config('app.url') . '/event/verify/' . $token;
    }

    public function regenerateQr()
    {
        $this->qr = self::generateQrCode();
        $this->save();

        return $this->qr;
    }

    public function getIsCheckedInAttribute()
    {
        return !empty($this->checked_in_at);
    }

    public function checkIn(int $userId)
    {
        $this->update([
            'status'         => 'attended',
            'checked_in_at'  => now(),
            'checked_in_by'  => $userId,
        ]);

        return $this;
    }

    public static function qrPng(string $content, int $size = 240): ?string
    {
        try {
            $level = \BaconQrCode\Common\ErrorCorrectionLevel::H();
            $qc = \BaconQrCode\Encoder\Encoder::encode($content, $level, 10);
            $matrix = $qc->getMatrix();
            $moduleSize = $matrix->getWidth();

            $pad = 4;
            $scale = (int) floor($size / ($moduleSize + $pad * 2));
            if ($scale < 1) {
                $scale = 1;
            }

            $imgSize = ($moduleSize + $pad * 2) * $scale;
            $img = imagecreatetruecolor($imgSize, $imgSize);
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, $imgSize, $imgSize, $white);

            for ($x = 0; $x < $moduleSize; $x++) {
                for ($y = 0; $y < $moduleSize; $y++) {
                    if ($matrix->get($x, $y)) {
                        imagefilledrectangle(
                            $img,
                            ($x + $pad) * $scale,
                            ($y + $pad) * $scale,
                            ($x + $pad) * $scale + $scale - 1,
                            ($y + $pad) * $scale + $scale - 1,
                            $black
                        );
                    }
                }
            }

            ob_start();
            imagepng($img);
            $png = ob_get_clean();
            imagedestroy($img);

            return $png ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}