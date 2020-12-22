<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            if (auth()->check()) {
                $image->creator_id = auth()->id();
            }
            if (empty($image->width)) {
                $data = getimagesize($image->getUrlAttribute());
                $image->width = $data[0];
                $image->height = $data[1];
            }
        });
    }

    public function getUrlAttribute()
    {
        return 'https://res.cloudinary.com/' . env('CLOUDINARY_CLOUD_NAME') . '/' . $this->filename;
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updater_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
