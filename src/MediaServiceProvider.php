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
}
