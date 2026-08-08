<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Voucher extends Model
{
    public $table = 'vouchers';

    protected $fillable = [
        'event_id',
        'code',
        'name',
        'type',
        'value',
        'description',
        'max_uses',
        'used_count',
        'is_single_use',
        'is_assignable',
        'valid_from',
        'valid_until',
        'status',
        'created_by',
    ];

    protected $casts = [
        'event_id'      => 'integer',
        'value'         => 'float',
        'max_uses'      => 'integer',
        'used_count'    => 'integer',
        'is_single_use' => 'boolean',
        'is_assignable' => 'boolean',
        'valid_from'    => 'datetime',
        'valid_until'   => 'datetime',
    ];

    public const TYPES = [
        'discount_percent' => 'Giảm % giá vé',
        'discount_fixed'   => 'Giảm số tiền cố định',
        'free_ticket'      => 'Vé miễn phí (100%)',
        'gift'             => 'Quà tặng',
        'priority_seat'    => 'Ưu tiên xếp chỗ',
    ];

    public const STATUS_LABELS = [
        'active'   => 'Đang hoạt động',
        'inactive' => 'Vô hiệu hóa',
        'expired'  => 'Hết hạn',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(VoucherAttendee::class, 'voucher_id');
    }

    public function attendees()
    {
        return $this->belongsToMany(Attendee::class, 'voucher_attendee', 'voucher_id', 'attendee_id')
            ->withPivot('assigned_by', 'assigned_at', 'used_at', 'note', 'status')
            ->withTimestamps();
    }

    public function scopeForEvent($query, ?int $eventId)
    {
        if ($eventId) {
            return $query->where(function ($q) use ($eventId) {
                $q->where('event_id', $eventId)->orWhereNull('event_id');
            });
        }

        return $query;
    }

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    public function getRemainingUsesAttribute(): ?int
    {
        if ($this->max_uses === null) {
            return null;
        }

        return max(0, $this->max_uses - $this->used_count);
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getDiscountLabelAttribute(): string
    {
        switch ($this->type) {
            case 'discount_percent':
                return 'Giảm ' . rtrim(rtrim(number_format($this->value, 2), '0'), '.') . '%';
            case 'discount_fixed':
                return 'Giảm ' . number_format($this->value, 0, ',', '.') . 'đ';
            case 'free_ticket':
                return 'Vé miễn phí 100%';
            case 'gift':
                return 'Quà tặng kèm vé';
            case 'priority_seat':
                return 'Ưu tiên xếp chỗ VIP';
            default:
                return '';
        }
    }

    public function calculateDiscount(?float $basePrice = null): float
    {
        switch ($this->type) {
            case 'discount_fixed':
                return (float) $this->value;
            case 'free_ticket':
                return $basePrice !== null ? (float) $basePrice : 0;
            case 'discount_percent':
                return $basePrice !== null ? round($basePrice * $this->value / 100, 2) : 0;
            default:
                return 0;
        }
    }

    public function isAvailable(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = Carbon::now();

        if ($this->valid_from && $now->lt(Carbon::parse($this->valid_from))) {
            return false;
        }

        if ($this->valid_until && $now->gt(Carbon::parse($this->valid_until))) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function syncStatusFromDates(): void
    {
        $now = Carbon::now();
        $status = $this->status;

        if ($this->valid_until && $now->gt(Carbon::parse($this->valid_until))) {
            $status = 'expired';
        }

        if ($status !== $this->status) {
            $this->update(['status' => $status]);
        }
    }

    public static function generateCode(string $prefix = ''): string
    {
        do {
            $code = strtoupper(($prefix ?: 'VCR') . '-' . Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
