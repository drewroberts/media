<?php

namespace DrewRoberts\Media\Support\YouTube;

class Duration
{
    public static function iso8601ToSeconds(?string $iso8601): ?int
    {
        if (! $iso8601) return null;
        if (! preg_match('/^PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?$/', $iso8601, $m)) {
            return null;
        }
        $h = (int)($m[1] ?? 0);
        $min = (int)($m[2] ?? 0);
        $s = (int)($m[3] ?? 0);
        return $h * 3600 + $min * 60 + $s;
    }
}
