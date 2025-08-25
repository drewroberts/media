<?php

namespace DrewRoberts\Media;

use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MediaServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        // String helper macros used by the models
        if (! Str::hasMacro('keepAlphanumericCharactersAndSpaces')) {
            Str::macro('keepAlphanumericCharactersAndSpaces', function ($value) {
                return preg_replace('/[^\w\s]/', '', $value);
            });
        }
        if (! Str::hasMacro('keepAlphanumericCharacters')) {
            Str::macro('keepAlphanumericCharacters', function ($value) {
                return preg_replace('/[^\w]/', '', $value);
            });
        }

        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('media')
            ->hasConfigFile()
            ->runsMigrations();
    }

    public function packageBooted(): void
    {
        // Auto-load package migrations from database/migrations without publishing
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
