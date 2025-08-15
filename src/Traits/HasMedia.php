<?php

namespace DrewRoberts\Media\Traits;

use Illuminate\Contracts\Routing\UrlGenerator;

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
        $cloudName = config('filesystem.disks.cloudinary.cloud_name');

        return $this->image === null
            ? url('img/ogimage.jpg')
            : "https://res.cloudinary.com/{$cloudName}/t_cover/{$this->image->filename}";
    }

    /**
     * Get a string path for the page image's placeholder.
     *
     * @return UrlGenerator|string
     */
    public function getPlaceholderPathAttribute()
    {
        $cloudName = config('filesystem.disks.cloudinary.cloud_name');

        return $this->image === null
            ? url('img/ogimage.jpg')
            : "https://res.cloudinary.com/{$cloudName}/t_coverplaceholder/{$this->image->filename}";
    }
}
