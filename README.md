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

You can publish the config file with:

```bash
php artisan vendor:publish --tag="media-config"
```

Or you can add the cloudinary disk to the filesystem config and set the environment variables for your Cloudinary account:

```php
return [
    ...
    'disks' => [
        ...
        'cloudinary' => [
            'driver' => 'cloudinary',
            'api_key' => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        ]
    ]
];
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
