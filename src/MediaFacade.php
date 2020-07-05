<?php

namespace Drewroberts\Media;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Drewroberts\Media\Media
 */
class MediaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'media';
    }
}
