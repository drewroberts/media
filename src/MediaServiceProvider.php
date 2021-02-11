<?php

declare(strict_types=1);

namespace DrewRoberts\Media;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MediaServiceProvider extends PackageServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        parent::boot();
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('media')
            ->hasConfigFile();
    }
}
