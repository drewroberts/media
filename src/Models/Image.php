<?php

namespace DrewRoberts\Media\Models;


use Illuminate\Database\Eloquent\Model;
use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasCreator;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Support\Traits\HasUpdater;

class Image extends BaseModel
{

    use HasCreator, HasUpdater, HasPackageFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($image) {
            if (empty($image->width)) {
                $data = getimagesize($image->getUrlAttribute());
                $image->width = $data[0];
                $image->height = $data[1];
            }
        });
    }

    public function getUrlAttribute()
    {
        return 'https://res.cloudinary.com/' . env('CLOUDINARY_CLOUD_NAME') . '/' . $this->filename;
    }

    public function videos()
    {
        return $this->hasMany(app('video'));
    }
}
