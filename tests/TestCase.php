<?php

namespace DrewRoberts\Media\Tests;

use DrewRoberts\Media\MediaServiceProvider;
use DrewRoberts\Media\Tests\Support\Models\User;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        foreach (config('media.models') as $class) {
            $class::createTable();
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            MediaServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('media.models', [
            'user' => User::class,
        ]);
    }
}
