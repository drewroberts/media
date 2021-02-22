<?php

namespace DrewRoberts\Media\Traits;


use DrewRoberts\Media\Models\Tag;

trait HasTags
{
    public function tags()
    {
        return $this->morphToMany(app('tag'), 'taggable');
    }

    public function attachTag(Tag $tag)
    {
        $this->tags()->save($tag);
    }

    public function detachTag(Tag $tag)
    {
        $this->tags()->detach($tag);
    }
}
