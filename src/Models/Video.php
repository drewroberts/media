<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'stream_scheduled_at' => 'datetime',
        'stream_started_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            if (auth()->check()) {
                $video->creator_id = auth()->id();
            }
        });

        static::saving(function ($video) {
            if (empty($video->identifier)) {
                throw new \Exception('Video must have an identifier on YouTube or Vimeo.');
            }
            if (empty($video->source)) {
                $video->source = 'youtube';
            }
            if (auth()->check()) {
                $video->updater_id = auth()->id();
            }
        });
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updater_id');
    }
}
