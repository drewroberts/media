<?php

// config for DrewRoberts/Media
return [
    // Named transformations used by this package when composing URLs
    'transforms' => [
        'cover' => 't_cover',
        'cover_placeholder' => 't_coverplaceholder',
    ],

    // Relative path used when a model has no image; wrapped with url() in code
    'fallback_image' => 'img/ogimage.jpg',

    // YouTube Data API V3 integration
    'youtube' => [
        // Read API key directly from the host app's environment without requiring published config
        // Use superglobals instead of env() to satisfy Larastan noEnvCallsOutsideOfConfig in package context
        'api_key' => $_ENV['YOUTUBE_API_KEY'] ?? $_SERVER['YOUTUBE_API_KEY'] ?? null,

        // Request options
        'timeout' => 8.0, // seconds
        'base_url' => 'https://www.googleapis.com/youtube/v3',

        // Preferred order for thumbnails
        'thumbnail_preference' => [
            'maxres', 'standard', 'high', 'medium', 'default',
        ],
    ],
];
