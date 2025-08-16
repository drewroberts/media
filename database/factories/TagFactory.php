<?php

namespace DrewRoberts\Media\Database\Factories;
use DrewRoberts\Media\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    public function definition()
    {
        $word = $this->faker->unique()->word;

        return [
            'name' => $word,
            'creator_id' => randomOrCreate(config('auth.providers.users.model')),
            'updater_id' => randomOrCreate(config('auth.providers.users.model')),
        ];
    }
}
