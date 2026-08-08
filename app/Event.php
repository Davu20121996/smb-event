<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Event extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    public $table = 'events';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'countdown_enabled',
        'registration_deadline',
        'meta_title',
        'meta_description',
        'favicon_url',
        'og_image',
        'calendar_enabled',
        'zalo_url',
        'fanpage_url',
        'show_gallery',
        'show_sponsors',
        'show_tickets',
        'about_description',
        'about_where',
        'about_when',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_active'            => 'boolean',
        'countdown_enabled'    => 'boolean',
        'calendar_enabled'     => 'boolean',
        'show_gallery'         => 'boolean',
        'show_sponsors'        => 'boolean',
        'show_tickets'         => 'boolean',
        'registration_deadline' => 'datetime',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300);
    }

    public function speakers()
    {
        return $this->hasMany(Speaker::class, 'event_id', 'id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'event_id', 'id');
    }

    public function venues()
    {
        return $this->hasMany(Venue::class, 'event_id', 'id');
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'event_id', 'id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'event_id', 'id');
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class, 'event_id', 'id');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'event_id', 'id');
    }

    public function amenities()
    {
        return $this->hasMany(Amenity::class, 'event_id', 'id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'event_id', 'id');
    }

    public function settings()
    {
        return $this->hasMany(Setting::class, 'event_id', 'id');
    }

    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class, 'event_id', 'id');
    }

    public function keyBenefits()
    {
        return $this->hasMany(KeyBenefit::class, 'event_id', 'id')->orderBy('sort_order');
    }

    public function getMobileBgImageUrlAttribute()
    {
        $media = $this->getMedia('mobile_bg')->first();

        return $media ? (parse_url($media->getUrl(), PHP_URL_PATH) ?: $media->getUrl()) : null;
    }

    public function getPcBgImageUrlAttribute()
    {
        $media = $this->getMedia('pc_bg')->first();

        return $media ? (parse_url($media->getUrl(), PHP_URL_PATH) ?: $media->getUrl()) : null;
    }

    public function getVenueAttribute()
    {
        $venue = $this->venues()->first();

        if (!$venue) {
            return $this->about_where ?? null;
        }

        $parts = array_filter([$venue->name, $venue->address]);

        return implode(', ', $parts);
    }
}
