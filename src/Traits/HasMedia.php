<?php

namespace DrewRoberts\Media\Traits;
use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Models\Video;

use Illuminate\Contracts\Routing\UrlGenerator;
use InvalidArgumentException;

trait HasMedia
{
    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function ogimage()
    {
        return $this->belongsTo(Image::class, 'ogimage_id');
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
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
