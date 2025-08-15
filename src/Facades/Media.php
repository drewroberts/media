<?php

namespace DrewRoberts\Media\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DrewRoberts\Media\Media
 */
class Media extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DrewRoberts\Media\Media::class;
    }
}
