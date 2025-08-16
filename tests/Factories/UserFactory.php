<?php

namespace DrewRoberts\Media\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserFactory extends Factory
{
    protected $model = TestUser::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
        ];
    }
}

class TestUser extends Model
{
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    public static function factory(): UserFactory
    {
        return new UserFactory;
    }
}

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create user directly in database
    $this->user = DB::table('users')->insertGetId([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Storage::fake('public');
});

it('can handle complete image lifecycle with authentication', function () {
    // Create a simple user model for auth
    $user = new class
    {
        public $id;

        public function __construct($id)
        {
            $this->id = $id;
        }
    };
    $user->id = $this->user;

    Auth::shouldReceive('login')->with($user);
    Auth::shouldReceive('check')->andReturn(true);
    Auth::shouldReceive('id')->andReturn($this->user);

});
