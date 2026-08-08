<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use SoftDeletes;

    public $table = 'prices';

    protected $fillable = [
        'name',
        'price',
        'created_at',
        'updated_at',
        'deleted_at',
        'event_id',
    ];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
