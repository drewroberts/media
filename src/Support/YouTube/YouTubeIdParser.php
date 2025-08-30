<?php

namespace DrewRoberts\Media\Support\YouTube;

class YouTubeIdParser
{
    /**
     * Extract a video ID from common YouTube URL formats or return ID as-is.
     */
    public static function parse(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        // If looks like a plain ID
        if (preg_match('/^[A-Za-z0-9_-]{6,}$/', $value)) {
            return $value;
        }

        // Normalize spaces, including non-breaking space (U+00A0)
        $value = preg_replace('/[\x{00A0}\s]+/u', '', $value) ?? $value;

        // youtu.be/<id>
        if (preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~i', $value, $m)) {
            return $m[1];
        }
        // watch?v=<id>
        if (preg_match('~[?&]v=([A-Za-z0-9_-]{6,})~i', $value, $m)) {
            return $m[1];
        }
        // shorts/<id> or embed/<id> or live/<id>
        if (preg_match('~/(shorts|embed|live)/([A-Za-z0-9_-]{6,})~i', $value, $m)) {
            return $m[2];
        }

        return null;
    }
}
