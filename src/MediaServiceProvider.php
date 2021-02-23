<?php

declare(strict_types=1);

namespace DrewRoberts\Media;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Models\Tag;
use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Policies\ImagePolicy;
use DrewRoberts\Media\Policies\TagPolicy;
use DrewRoberts\Media\Policies\VideoPolicy;
use Illuminate\Support\Str;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class MediaServiceProvider extends TipoffServiceProvider
{
    public function boot()
    {
        parent::boot();

        Str::macro('keepAlphanumericCharactersAndSpaces', static function ($value) {
            return preg_replace('/[^\w\s]/', '', $value);
        });

        Str::macro('keepAlphanumericCharacters', static function ($value) {
            return preg_replace('/[^\w]/', '', $value);
        });
    }

    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasPolicies([
                Image::class => ImagePolicy::class,
                Tag::class => TagPolicy::class,
                Video::class => VideoPolicy::class,
            ])
            ->name('media')
            ->hasConfigFile();
    }
}
