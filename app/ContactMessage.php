<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public $table = 'contact_messages';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'event_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'event_id' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function scopeFromHome($query)
    {
        return $query->where('event_id', 0);
    }

    public function getSourceLabelAttribute()
    {
        if ($this->event_id == 0) {
            return 'Home Page';
        }

        return $this->event->name ?? 'General';
    }
}
