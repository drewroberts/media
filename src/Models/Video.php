<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;


class Video extends Model
{
    use HasPackageFactory, HasCreator, HasUpdater;

    protected $guarded = ['id'];

    protected $casts = [
        'stream_scheduled_at' => 'datetime',
        'stream_started_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($video) {
            if (empty($video->identifier)) {
                throw new \Exception('Video must have an identifier on YouTube or Vimeo.');
            }
            if (empty($video->source)) {
                $video->source = 'youtube';
            }
        });
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
    
}
