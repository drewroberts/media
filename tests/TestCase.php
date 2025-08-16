<?php

namespace DrewRoberts\Media\Tests;

use DrewRoberts\Media\MediaServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'DrewRoberts\\Media\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            MediaServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set up auth configuration for testing
        config()->set('auth.providers.users.model', TestUser::class);

        // Create users table for testing
        $app['db']->connection()->getSchemaBuilder()->create('users', function ($table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
}

// Create a test user model for testing purposes
class TestUser extends \Illuminate\Foundation\Auth\User
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    protected static function newFactory()
    {
        return new class extends \Illuminate\Database\Eloquent\Factories\Factory
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
        };
    }
}

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = TestUser::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    Storage::fake('public');
});
