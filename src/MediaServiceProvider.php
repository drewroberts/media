<?php

declare(strict_types=1);

namespace DrewRoberts\Media;

use Tipoff\Media\Models\Image;
use Tipoff\Media\Models\Tag;
use Tipoff\Media\Models\Video;
use Tipoff\Support\TipoffPackage;
use Tipoff\Support\TipoffServiceProvider;

class MediaServiceProvider extends TipoffServiceProvider
{
    public function configureTipoffPackage(TipoffPackage $package): void
    {
        $package
            ->name('media')
            ->hasConfigFile();
    }
}
