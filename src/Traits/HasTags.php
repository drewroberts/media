<?php

namespace DrewRoberts\Media\Traits;


trait HasTags
{
    public function tags()
    {
        return $this->morphToMany(app('tag'), 'taggable');
    }
}
