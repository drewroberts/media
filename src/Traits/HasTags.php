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

    public function attachTagByName(string $name, $type = null)
    {
        $tag = Tag::findOrCreateFromString($name, $type);

        $this->attachTag($tag);
    }

    public function detachTag(Tag $tag)
    {
        $this->tags()->detach($tag);
    }

    public function syncTags($tags)
    {
        $ids = collect($tags)->pluck('id')->toArray();

        $this->tags()->sync($ids);
    }
}
