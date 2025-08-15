<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Video extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'stream_scheduled_at' => 'datetime',
        'stream_started_at' => 'datetime',
        'published_at' => 'datetime',
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
                throw new \Exception('Video must have an identifier on YouTube or Vimeo.');
            }
            if (empty($video->source)) {
                $video->source = 'youtube';
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
}
