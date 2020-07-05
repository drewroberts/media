<?php

namespace Drewroberts\Media;

use Drewroberts\Media\Commands\MediaCommand;
use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/media.php' => config_path('media.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/media'),
            ], 'views');

            if (! class_exists('CreatePackageTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_media_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_media_table.php'),
                ], 'migrations');
            }

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
