<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CompanyProfileItem extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    public $table = 'company_profile_items';

    protected $appends = [
        'image',
    ];

    protected $fillable = [
        'section',
        'title',
        'category',
        'description',
        'link',
        'sort_order',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SECTIONS = [
        'values'     => 'Core Values',
        'why_us'     => 'Why Choose Us',
        'services'   => 'Services',
        'solutions'  => 'Solutions',
        'process'    => 'Implementation Process',
        'roles'      => 'Team Capabilities',
        'models'     => 'Work Models',
        'partners'   => 'Partners',
        'clients'    => 'Clients',
        'commitments'=> 'Commitments',
        'warranty'   => 'Warranty Process',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(80)->height(80);
    }

    public function getImageAttribute()
    {
        $file = $this->getMedia('image')->last();

        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
        }

        return $file;
    }
}
