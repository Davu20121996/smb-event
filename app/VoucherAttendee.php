<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoucherAttendee extends Model
{
    public $table = 'voucher_attendee';

    protected $fillable = [
        'voucher_id',
        'attendee_id',
        'assigned_by',
        'assigned_at',
        'used_at',
        'note',
        'status',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'used_at'     => 'datetime',
    ];

    public const STATUS_LABELS = [
        'assigned' => 'Đã gán',
        'used'     => 'Đã sử dụng',
        'expired'  => 'Hết hạn',
        'revoked'  => 'Thu hồi',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}