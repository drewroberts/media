<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            if (auth()->check()) {
                $video->creator_id = auth()->id();
            }
        });
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function creator()
    {
        return $this->belongsTo(\Illuminate\Foundation\Auth\User::class, 'creator_id');
    }
}
