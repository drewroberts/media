<?php

namespace DrewRoberts\Media\Tests;

use Laravel\Nova\NovaCoreServiceProvider;
use DrewRoberts\Media\MediaServiceProvider;
use DrewRoberts\Media\Tests\Support\Models\User;
use DrewRoberts\Media\Tests\Support\Providers\NovaPackageServiceProvider;
use Tipoff\Support\SupportServiceProvider;
use Tipoff\TestSupport\BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
            MediaServiceProvider::class,
            NovaCoreServiceProvider::class,
            NovaPackageServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('tipoff.model_class.user', User::class);
    }
}
