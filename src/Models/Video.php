<?php

namespace DrewRoberts\Media\Models;

use DrewRoberts\Media\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Video extends Model
{
    use HasFactory, HasTags;

    protected $guarded = ['id'];

    protected $casts = [
        'stream_scheduled_at' => 'datetime',
        'stream_started_at' => 'datetime',
        'published_at' => 'datetime',
        'duration' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->creator_id = Auth::id();
            }
        });

        static::saving(function ($video) {
            if (empty($video->identifier)) {
                $video->identifier = uniqid('vid_', true);
            }
            if (empty($video->source)) {
                $video->source = 'youtube';
            }
            if (Auth::check()) {
                $video->updater_id = Auth::id();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'updater_id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * Get the public YouTube short URL for this video, if available.
     */
    public function youtubeUrl(): ?string
    {
        if (empty($this->identifier)) {
            return null;
        }

        // Use the youtu.be short link format
        return sprintf('https://youtu.be/', $this->identifier);
    }
}
