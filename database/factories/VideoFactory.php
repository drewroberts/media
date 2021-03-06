<?php

namespace DrewRoberts\Media\Database\Factories;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition()
    {
        $word = $this->faker->unique()->word;

        return [
            'identifier'   => $this->faker->unique->isbn10,
            'source'       => $this->faker->randomElement(['youtube', 'vimeo', 'other']),
            'name'         => $this->faker->unique->sentence,
            'title'        => $word,
            'description'  => $this->faker->sentences(1, true),
            'image_id'     => randomOrCreate(app('image')),
            'creator_id'   => randomOrCreate(app('user')),
            'updater_id'   => randomOrCreate(app('user')),
        ];
    }
}
