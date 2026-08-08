<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
    use SoftDeletes;

    public $table = 'permissions';

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('auth_gates_permissions'));
        static::deleted(fn () => Cache::forget('auth_gates_permissions'));
    }
}
