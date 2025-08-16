# Laravel Package for opinionated usage of Media (images & videos)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/drewroberts/media.svg?style=flat-square)](https://packagist.org/packages/drewroberts/media)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/drewroberts/media/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/drewroberts/media/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/drewroberts/media/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/drewroberts/media/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/drewroberts/media.svg?style=flat-square)](https://packagist.org/packages/drewroberts/media)

The media package utilizes Cloudinary for images and YouTube for videos. Createe a free cloudinary account for each laravel application utilitizing this package here:

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

Add your Cloudinary credentials to your `.env` file:

```
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
CLOUDINARY_KEY=
CLOUDINARY_SECRET=
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_UPLOAD_PRESET=your_upload_preset
CLOUDINARY_NOTIFICATION_URL=
CLOUDINARY_UPLOAD_ROUTE=
CLOUDINARY_UPLOAD_ACTION=
```

> [!NOTE]  
> You can get your `CLOUDINARY_URL` from your [Cloudinary console](https://cloudinary.com/console). It typically looks like this: `cloudinary://API_KEY:API_SECRET@CLOUD_NAME`. Make sure to replace `API_KEY`, `API_SECRET`, and `CLOUD_NAME` with your actual Cloudinary credentials.

Add a new `cloudinary` key to your `config/filesystems.php` disk key like so:

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

You can publish the config file with:

```bash
php artisan vendor:publish --tag="media-config"
```

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
