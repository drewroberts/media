# Laravel Package for opinionated usage of Media (images & videos)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/drewroberts/media.svg?style=flat-square)](https://packagist.org/packages/drewroberts/media)
![Tests](https://github.com/drewroberts/media/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/drewroberts/media.svg?style=flat-square)](https://packagist.org/packages/drewroberts/media)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require drewroberts/media
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="DrewRoberts\Media\MediaServiceProvider" --tag="config"
```

Add the cloudinary disk to the filesystem config and set the environment variables for your Cloudinary account.

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

## Models

We include the following models in this package:

**List of Models**

- Image
- Tag
- Video

For each of these models, this package implements an [authorization policy](https://laravel.com/docs/8.x/authorization) that extends the roles and permissions approach of the [tipoff/authorization](https://github.com/tipoff/authorization) package. The policies for each model in this package are registered through the package and do not need to be registered manually.

The models also have [Laravel Nova resources](https://nova.laravel.com/docs/3.0/resources/) in this package and they are also registered through the package and do not need to be registered manually.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email packages@drewroberts.com instead of using the issue tracker.

## Credits

- [Drew Roberts](https://github.com/drewroberts)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
