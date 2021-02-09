<?php

namespace DrewRoberts\Media\Models;

use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasUpdater;
use Tipoff\Support\Traits\HasPackageFactory;

class Video extends BaseModel
{
    use HasCreator, HasUpdater, HasPackageFactory;

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
        return $this->belongsTo(app('image'));
    }
}
