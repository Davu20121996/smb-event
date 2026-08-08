<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class KeyBenefit extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    public $table = 'key_benefits';

    protected $fillable = [
        'icon',
        'title',
        'description',
        'sort_order',
        'event_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(80)->height(80);
    }

    public function getIconImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('icon') ?: null;
    }
}
