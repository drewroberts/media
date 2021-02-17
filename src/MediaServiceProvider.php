<?php

declare(strict_types=1);

namespace DrewRoberts\Media;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Models\Tag;
use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Policies\ImagePolicy;
use DrewRoberts\Media\Policies\TagPolicy;
use DrewRoberts\Media\Policies\VideoPolicy;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class MediaServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->hasPolicies([
                Image::class => ImagePolicy::class,
                Tag::class => TagPolicy::class,
                Video::class => VideoPolicy::class,
            ])
            ->name('media');
    }
}
