<?php

namespace DrewRoberts\Media\Database\Factories;
use DrewRoberts\Media\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition()
    {
        $number = $this->faker->numberBetween(1, 1000000);
        $word = $this->faker->word;
        $filename = $word.'-'.$number.'.jpg';

        return [
            'filename' => $filename,
            'width' => $this->faker->numberBetween(320, 1920),
            'height' => $this->faker->numberBetween(320, 1920),
            'description' => $this->faker->sentences(1, true),
            'alt' => $this->faker->word,
            'credit' => $this->faker->name,
            // Use the configured auth user model to avoid hard-coding App\\Models\\User
            'creator_id' => randomOrCreate(config('auth.providers.users.model')),
            'updater_id' => randomOrCreate(config('auth.providers.users.model')),
        ];
    }
}
