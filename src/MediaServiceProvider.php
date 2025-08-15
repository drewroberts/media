<?php

namespace DrewRoberts\Media;

use DrewRoberts\Media\Commands\MediaCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MediaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('media')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_media_table')
            ->hasCommand(MediaCommand::class);
    }
}
