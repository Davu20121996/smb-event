<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    public $table = 'posts';

    protected $appends = [
        'cover',
        'thumbnail',
    ];

    protected $fillable = [
        'title',
        'slug',
        'tag',
        'excerpt',
        'content',
        'is_published',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
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
}
