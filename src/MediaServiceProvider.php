<?php

namespace DrewRoberts\Media;

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
            ->hasMigrations([
                '2015_05_20_100000_create_images_table',
                '2015_05_20_110000_create_videos_table',
                '2015_05_20_120000_create_tags_table',
                '2015_05_20_121000_create_taggables_table',
            ])
            ->runsMigrations();
    }
}
