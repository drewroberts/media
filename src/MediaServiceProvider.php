<?php

declare(strict_types=1);

namespace DrewRoberts\Media;

use Tipoff\Media\Models\Image;
use Tipoff\Media\Models\Tag;
use Tipoff\Media\Models\Video;
use Tipoff\Media\Policies\ImagePolicy;
use Tipoff\Media\Policies\TagPolicy;
use Tipoff\Media\Policies\VideoPolicy;
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
            ->name('media')
            ->hasConfigFile();
    }
}
