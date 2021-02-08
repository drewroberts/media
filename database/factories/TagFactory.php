<?php

namespace DrewRoberts\Media\Database\Factories;

use DrewRoberts\Media\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        $word = $this->faker->unique()->word;

        return [
            'name'         => $word,
            'slug'         => $word,
            'creator_id'   => randomOrCreate(config('media.models.user')),
            'updater_id'   => randomOrCreate(config('media.models.user')),
        ];
    }
}
