<?php

namespace DrewRoberts\Media\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug()
    {
        static::saving(static function (Model $model) {
            $model->slug = $model->generateSlug($model->name);
        });
    }

    protected function generateSlug($value)
    {
        return Str::slug($value);
    }
}
