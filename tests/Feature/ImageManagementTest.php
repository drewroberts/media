<?php

use DrewRoberts\Media\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = \DrewRoberts\Media\Tests\TestUser::factory()->create();
    Storage::fake('public');
});

it('can handle complete image lifecycle with authentication', function () {
    Auth::login($this->user);

    // Create image
    $image = Image::create([
        'filename' => '/uploads/test-lifecycle.jpg',
        'width' => 1920,
        'height' => 1080,
        'description' => 'Lifecycle test image',
        'alt' => 'Test alt text',
        'credit' => 'Test Photographer',
    ]);

    expect($image->creator_id)->toBe($this->user->id)
        ->and($image->filename)->toBe('/uploads/test-lifecycle.jpg');

    // Update image
    $image->update([
        'description' => 'Updated description',
        'alt' => 'Updated alt text',
    ]);

    $image->refresh();

    expect($image->updater_id)->toBe($this->user->id)
        ->and($image->description)->toBe('Updated description');
});

it('can organize images by dimensions and aspect ratios', function () {
    // Create various images
    $images = [
        ['filename' => '/small-square.jpg', 'width' => 300, 'height' => 300],
        ['filename' => '/medium-landscape.jpg', 'width' => 1200, 'height' => 800],
        ['filename' => '/large-landscape.jpg', 'width' => 1920, 'height' => 1080],
        ['filename' => '/portrait.jpg', 'width' => 800, 'height' => 1200],
        ['filename' => '/large-square.jpg', 'width' => 1000, 'height' => 1000],
    ];

    foreach ($images as $imageData) {
        Image::create($imageData);
    }

    expect(Image::count())->toBe(5)
        ->and(Image::byDimensions(800, 600)->count())->toBe(4)
        ->and(Image::byDimensions(1000, 1000)->count())->toBe(3)
        ->and(Image::byAspectRatio('landscape')->count())->toBe(2)
        ->and(Image::byAspectRatio('portrait')->count())->toBe(1)
        ->and(Image::byAspectRatio('square')->count())->toBe(2);
});

it('can handle image gallery operations', function () {
    Auth::login($this->user);

    // Create a gallery of images
    $gallery = collect(range(1, 5))->map(function ($i) {
        return Image::create([
            'filename' => "/gallery/image-{$i}.jpg",
            'width' => 800 + ($i * 100),
            'height' => 600,
            'alt' => "Gallery image {$i}",
        ]);
    });

    expect($gallery)->toHaveCount(5);

    // Filter gallery by minimum dimensions
    $largeImages = Image::byDimensions(1000, 600)->get();
    expect($largeImages)->toHaveCount(3);

    // Get all landscape images from gallery
    $landscapeImages = Image::byAspectRatio('landscape')->get();
    expect($landscapeImages)->toHaveCount(5);
});

it('can validate image file extensions', function () {
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];

    foreach ($validExtensions as $ext) {
        $image = Image::create([
            'filename' => "/test.{$ext}",
            'width' => 800,
            'height' => 600,
        ]);

        expect($image->extension)->toBe($ext);
    }
});

it('can process bulk image operations', function () {
    Auth::login($this->user);

    // Create multiple images
    $imageData = collect(range(1, 10))->map(function ($i) {
        return [
            'filename' => "/bulk/image-{$i}.jpg",
            'width' => rand(400, 2000),
            'height' => rand(300, 1500),
            'description' => "Bulk image {$i}",
        ];
    });

    $images = collect();
    foreach ($imageData as $data) {
        $images->push(Image::create($data));
    }

    expect($images)->toHaveCount(10);

    // Update all images with credit
    Image::where('filename', 'like', '/bulk/%')->update([
        'credit' => 'Bulk Photographer',
    ]);

    $updatedImages = Image::where('credit', 'Bulk Photographer')->get();
    expect($updatedImages)->toHaveCount(10);
});

it('can search and filter images', function () {
    // Create test images
    Image::create(['filename' => '/nature/sunset.jpg', 'width' => 1920, 'height' => 1080, 'alt' => 'Beautiful sunset']);
    Image::create(['filename' => '/nature/forest.jpg', 'width' => 1200, 'height' => 800, 'alt' => 'Dense forest']);
    Image::create(['filename' => '/city/skyline.jpg', 'width' => 2000, 'height' => 1000, 'alt' => 'City skyline']);
    Image::create(['filename' => '/portrait/person.jpg', 'width' => 800, 'height' => 1200, 'alt' => 'Portrait photo']);

    // Search by filename pattern
    $natureImages = Image::where('filename', 'like', '/nature/%')->get();
    expect($natureImages)->toHaveCount(2);

    // Filter by alt text
    $sunsetImages = Image::where('alt', 'like', '%sunset%')->get();
    expect($sunsetImages)->toHaveCount(1);

    // Complex query combining filters
    $largeLandscapeImages = Image::byDimensions(1500, 900)
        ->byAspectRatio('landscape')
        ->get();

    expect($largeLandscapeImages)->toHaveCount(2);
});
