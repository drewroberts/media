<?php

// config for DrewRoberts/Media
return [

    // Use env() with default null values (allowed inside config files)

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | An HTTP or HTTPS URL to notify your application (a webhook) when the process of uploads, deletes, and any API
    | that accepts notification_url has completed.
    |
    | @todo Create a notification url within this media package to handle saving response to database..
    |
    */
    env('CLOUDINARY_NOTIFICATION_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. Cloudinary is a cloud hosted
    | media management service for all file uploads, storage, delivery and transformation needs.
    |
    |
    */
    env('CLOUDINARY_URL', null),
    env('CLOUDINARY_KEY', null),
    env('CLOUDINARY_SECRET', null),
    env('CLOUDINARY_CLOUD_NAME', null),
    env('CLOUDINARY_UPLOAD_PRESET', null),
    env('CLOUDINARY_UPLOAD_ROUTE', null),
    env('CLOUDINARY_UPLOAD_ACTION', null),

];
