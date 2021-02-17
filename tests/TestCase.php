<?php

namespace DrewRoberts\Media\Tests;

use DrewRoberts\Media\MediaServiceProvider;
use DrewRoberts\Media\Tests\Support\Models\User;
use Tipoff\Support\SupportServiceProvider;
use Tipoff\TestSupport\BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            SupportServiceProvider::class,
            MediaServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('tipoff.model_class.user', User::class);
        $app['config']->set('filesystem.disks.cloudinary.cloud_name', 'test');
    }
}
