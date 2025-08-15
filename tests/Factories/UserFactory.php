<?php

namespace DrewRoberts\Media\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

class UserFactory extends Factory
{
    protected $model = TestUser::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ];
    }
}

class TestUser extends Model
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    public static function factory()
    {
        return new UserFactory;
    }
}
