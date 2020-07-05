<?php

namespace DrewRoberts\Media;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DrewRoberts\Media\Media
 */
class MediaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'media';
    }
}
