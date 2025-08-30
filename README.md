# Laravel Package for opinionated usage of Media (images & videos)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/drewroberts/media.svg?style=flat-square)](https://packagist.org/packages/drewroberts/media)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/drewroberts/media/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/drewroberts/media/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/drewroberts/media/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/drewroberts/media/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/drewroberts/media.svg?style=flat-square)](https://packagist.org/packages/drewroberts/media)

The media package utilizes Cloudinary for images and YouTube for videos. Create a free Cloudinary account for each Laravel application utilizing this package here:

- [Cloudinary](https://cloudinary.com)

## Models

The following models are included in this package:

**List of Models**

- Image
- Tag
- Video

## Installation

You can install the package via composer:

```bash
composer require drewroberts/media
```

### Environment variables (Cloudinary Laravel v3)

Add your Cloudinary credentials to your `.env` file. Choose one of the following options:

Required env variables:

```
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_KEY=
CLOUDINARY_SECRET=
FILESYSTEM_DISK=cloudinary
```

> [!NOTE]  
> You can get your credentials from your [Cloudinary console](https://cloudinary.com/console).

### Configure the Cloudinary disk (required)

Add a `cloudinary` disk to your `config/filesystems.php`:

```php
return [
    ...
    'disks' => [
        ...
        'cloudinary' => [
            'driver' => 'cloudinary',
            'key' => env('CLOUDINARY_KEY'),
            'secret' => env('CLOUDINARY_SECRET'),
            'cloud' => env('CLOUDINARY_CLOUD_NAME'),
            'url' => env('CLOUDINARY_URL'),
            'secure' => (bool) env('CLOUDINARY_SECURE', true),
            'prefix' => env('CLOUDINARY_PREFIX'),
        ]
    ]
];
```

> The package expects `filesystems.disks.cloudinary.cloud` to be set. If missing, URL helpers will throw an informative exception.

### Media package config (optional)

This package ships with `config/media.php` for its own options. You can publish and customize it:

```
php artisan vendor:publish --tag=media-config
```

Available options and defaults:

```php
return [
    'transforms' => [
        'cover' => 't_cover',
        'cover_placeholder' => 't_coverplaceholder',
    ],

    // Relative path; wrapped with url() in code when no image exists
    'fallback_image' => 'img/ogimage.jpg',

    // YouTube Data API V3 integration (Non-OAuth)
    'youtube' => [
        // Set in your application's .env
        'api_key' => env('YOUTUBE_API_KEY'),

        // HTTP request options
        'timeout' => 8.0, // seconds
        'base_url' => 'https://www.googleapis.com/youtube/v3',

        // Preferred order for selecting thumbnails
        'thumbnail_preference' => [
            'maxres', 'standard', 'high', 'medium', 'default',
        ],
    ],
];
```

> [!NOTE]
> In your Cloudinary console, create a Named Transformation called `t_cover` sized to 1200x630 pixels. This package references that name to render cover images.
>
> Create another Named Transformation `t_coverplaceholder` sized to 120x63 pixels. This is intended as a lightweight loading placeholder, while the full `t_cover` image can be lazy-loaded by the frontend.

### YouTube Data API v3 (Non-OAuth)

This package integrates with the official YouTube Data API v3 using an API key (no OAuth). It is used to parse YouTube links, fetch video details (title, description, duration, publish date, statistics, etc.), and pick the best thumbnail.

Required env variable:

```
YOUTUBE_API_KEY=
```

Notes

- Obtain an API key from your [Google Cloud Console](https://console.developers.google.com/) and enable the YouTube Data API v3 for your project.
- You can customize request timeout, base URL, and thumbnail selection order via `config/media.php` under the `youtube` key (publish the config if needed).
- API quota or configuration issues will surface as clear exceptions; ensure the key is set in environments where the service is used.

## Migrations

This package auto-runs its migrations and does not publish them.

The following tables are created:

- images
- videos
- tags
- taggables

Note: Because migrations are auto-loaded, you don’t need to vendor:publish them. If you need to customize the schema, override with your own migrations in your application.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Drew Roberts](https://github.com/drewroberts)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
