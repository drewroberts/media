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
];
