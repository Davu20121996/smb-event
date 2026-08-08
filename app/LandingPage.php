<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LandingPage extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    public $table = 'landing_pages';

    protected $appends = [
        'cover',
        'thumbnail',
        'pdf_file',
        'speaker_avatar',
    ];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'form_title',
        'button_title',
        'crm_tag',
        'pdf_enabled',
        'pdf_source',
        'pdf_url',
        'download_title',
        'download_button_title',
        'report_url',
        'is_published',
        'countdown_enabled',
        'registration_deadline',
        'key_benefits',
        'agenda',
        'speaker_name',
        'speaker_role',
        'speaker_company',
        'speaker_bio',
        'calendar_enabled',
        'zalo_url',
        'fanpage_url',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'pdf_enabled'          => 'boolean',
        'is_published'         => 'boolean',
        'countdown_enabled'    => 'boolean',
        'registration_deadline' => 'datetime',
        'key_benefits'         => 'array',
        'agenda'               => 'array',
        'calendar_enabled'     => 'boolean',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
        $this->addMediaConversion('card')->width(640)->height(480);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('thumbnail')->singleFile();
        $this->addMediaCollection('pdf')->singleFile();
        $this->addMediaCollection('speaker_avatar')->singleFile();
    }

    public function getCoverAttribute()
    {
        $file = $this->getMedia('cover')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->card      = $file->getUrl('card');
        }

        return $file;
    }

    public function getThumbnailAttribute()
    {
        $file = $this->getMedia('thumbnail')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->card      = $file->getUrl('card');
        }

        return $file;
    }

    public function getPdfFileAttribute()
    {
        $file = $this->getMedia('pdf')->last();

        if ($file) {
            $file->url = $file->getUrl();
        }

        return $file;
    }

    public function getSpeakerAvatarAttribute()
    {
        $file = $this->getMedia('speaker_avatar')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->card      = $file->getUrl('card');
        }

        return $file;
    }

    public function leads()
    {
        return $this->hasMany(LandingLead::class, 'landing_page_id');
    }
}
