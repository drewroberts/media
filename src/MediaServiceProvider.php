<?php

namespace DrewRoberts\Media;

use DrewRoberts\Media\Commands\MediaCommand;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/media.php' => config_path('media.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/media'),
            ], 'views');

            $this->commands([
                MediaCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'media');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/media.php', 'media');
    }

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
            ->hasMigration([
                '2015_05_20_100000_create_images_table',
                '2015_05_20_110000_create_videos_table',
                '2015_05_20_120000_create_tag_tables',
            ])
            ->hasCommand(MediaCommand::class);
    }

    /**
     * Using packageBooted lifecycle hooks to override the migration file name.
     * We want to keep the old filename for now.
     */
    public function packageBooted()
    {
        foreach ($this->package->migrationFileNames as $migrationFileName) {
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    $this->package->basePath("/../database/migrations/{$migrationFileName}.php.stub") => database_path('migrations/' . Str::finish($migrationFileName, '.php')),
                ], "{$this->package->name}-migrations");
            }
        }
    }
}
