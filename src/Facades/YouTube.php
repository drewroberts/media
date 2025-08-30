<?php

namespace DrewRoberts\Media\Facades;

use DrewRoberts\Media\Support\YouTube\YouTubeService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null parseId(string $input)
 * @method static \DrewRoberts\Media\Support\YouTube\VideoData fetch(string $videoId)
 * @method static \DrewRoberts\Media\Models\Image|null ensureThumbnailImage(\DrewRoberts\Media\Support\YouTube\VideoData $data)
 */
class YouTube extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return YouTubeService::class;
    }
}
