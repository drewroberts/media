<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Tests\Support\Providers;

use DrewRoberts\Media\Nova\Image;
use DrewRoberts\Media\Nova\Tag;
use DrewRoberts\Media\Nova\Video;
use Tipoff\TestSupport\Providers\BaseNovaPackageServiceProvider;

class NovaPackageServiceProvider extends BaseNovaPackageServiceProvider
{
    public static array $packageResources = [
        Image::class,
        Tag::class,
        Video::class,
    ];
}
