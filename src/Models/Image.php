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
        });
    }

    public function getUrlAttribute()
    {
        return 'https://res.cloudinary.com/'.env('').'/'.$this->filename;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

}
