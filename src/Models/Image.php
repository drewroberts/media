<?php

namespace DrewRoberts\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Tipoff\Support\Traits\HasPackageFactory;

class Image extends Model
{
    use HasPackageFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            if (auth()->check()) {
                $image->creator_id = auth()->id();
            }
        });

        static::saving(function ($image) {
            if (auth()->check()) {
                $image->updater_id = auth()->id();
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
        return 'https://res.cloudinary.com/' . config('media.cloudinary_cloud_name') . '/' . $this->filename;
    }

    public function creator()
    {
        return $this->belongsTo(config('media.models.user'), 'creator_id');
    }

    public function updater()
    {
        return $this->belongsTo(config('media.models.user'), 'updater_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
