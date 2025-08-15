<?php

namespace DrewRoberts\Media\Tests;

use DrewRoberts\Media\MediaServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;
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
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Load package migrations
        $migration = include __DIR__.'/../database/migrations/2015_05_20_100000_create_images_table.php';
        $migration->up();

        // If you have a tags migration
        $tagsMigration = include __DIR__.'/../database/migrations/2015_05_20_200000_create_tags_table.php';
        $tagsMigration->up();

        // Create users table for testing
        $app['db']->connection()->getSchemaBuilder()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
}

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = config('auth.providers.users.model')::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
    Storage::fake('public');
});
