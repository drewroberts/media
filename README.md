# Laravel Package for opinionated usage of Media (images & videos)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/drewroberts/media.svg?style=flat-square)](https://packagist.org/packages/drewroberts/media)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/drewroberts/media/run-tests?label=tests)](https://github.com/drewroberts/media/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/drewroberts/media.svg?style=flat-square)](https://packagist.org/packages/drewroberts/media)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require drewroberts/media
```

Add the cloudinary disk to the filesystem config and set the environment variables for your Cloudinary account.

```php
// config/filesystem.php
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

The migrations will run from the package. You can extend the Models from the package if you need additional classes or functions added to them. 

#### Registering the Nova resources

If you would like to use the Nova resources included with this package, you need to register it manually in your `NovaServiceProvider` in the `boot` method.

```php
Nova::resources([
    \DrewRoberts\Media\Nova\Image::class,
    \DrewRoberts\Media\Nova\Video::class,
]);
```

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
