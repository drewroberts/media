<?php

namespace DrewRoberts\Media\Traits;

use Illuminate\Contracts\Routing\UrlGenerator;
use InvalidArgumentException;

trait HasMedia
{
    public function image()
    {
        return $this->belongsTo(app('image'));
    }

    public function ogimage()
    {
        return $this->belongsTo(app('image'), 'ogimage_id');
    }

    public function video()
    {
        return $this->belongsTo(app('video'));
    }

    /**
     * Get a string path for the page image.
     *
     * @return UrlGenerator|string
     */
    public function getImagePathAttribute()
    {
        $cloudName = config('filesystems.disks.cloudinary.cloud');
        if (! $cloudName) {
            throw new InvalidArgumentException('Cloudinary disk misconfigured: set filesystems.disks.cloudinary.cloud');
        }

        $transform = config('media.transforms.cover', 't_cover');
        $fallback = config('media.fallback_image', 'img/ogimage.jpg');

        return $this->image === null
            ? url($fallback)
            : "https://res.cloudinary.com/{$cloudName}/{$transform}/{$this->image->filename}";
    }

    /**
     * Get a string path for the page image's placeholder.
     *
     * @return UrlGenerator|string
     */
    public function getPlaceholderPathAttribute()
    {
        $cloudName = config('filesystems.disks.cloudinary.cloud');
        if (! $cloudName) {
            throw new InvalidArgumentException('Cloudinary disk misconfigured: set filesystems.disks.cloudinary.cloud');
        }

        $transform = config('media.transforms.cover_placeholder', 't_coverplaceholder');
        $fallback = config('media.fallback_image', 'img/ogimage.jpg');

        return $this->image === null
            ? url($fallback)
            : "https://res.cloudinary.com/{$cloudName}/{$transform}/{$this->image->filename}";
    }
}
