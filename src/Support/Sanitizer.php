<?php

namespace DrewRoberts\Media\Support;

class Sanitizer
{
    public static function keepAlphanumericCharactersAndSpaces(string $value): string
    {
        return preg_replace('/[^\w\s]/', '', $value) ?? '';
    }

    public static function keepAlphanumericCharacters(string $value): string
    {
        return preg_replace('/[^\w]/', '', $value) ?? '';
    }
}
