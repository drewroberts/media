<?php

use DrewRoberts\Media\Models\Image;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = createUser();
    Storage::fake('public');
});

describe('Image Management', function () {
    describe('Lifecycle Operations', function () {
        test('creates images with proper creator tracking', function () {
            $user = authenticateUser();

            $image = createImage(sampleImageData());

            expect($image)
                ->toHaveCreator($user->id)
                ->filename->toBe('/sample-image.jpg');
        });

        test('updates images with proper updater tracking', function () {
            $user = authenticateUser();
            $image = createImage();

            $image->update(['description' => 'Updated', 'alt' => 'Updated alt']);

            expect($image->fresh())
                ->toHaveUpdater($user->id)
                ->description->toBe('Updated');
        });

        test('handles bulk image operations efficiently', function () {
            $user = authenticateUser();

            $images = collect(range(1, 10))
                ->map(fn ($i) => createImage(['filename' => "/bulk/image-{$i}.jpg"]));

            expect($images)->toHaveCount(10);

            Image::where('filename', 'like', '/bulk/%')->update(['credit' => 'Bulk Photographer']);

            expect(Image::where('credit', 'Bulk Photographer')->get())->toHaveCount(10);
        });
    });

    describe('Dimension and Aspect Ratio Handling', function () {
        beforeEach(function () {
            collect([
                ['filename' => '/small-square.jpg', 'width' => 300, 'height' => 300],
                ['filename' => '/medium-landscape.jpg', 'width' => 1200, 'height' => 800],
                ['filename' => '/large-landscape.jpg', 'width' => 1920, 'height' => 1080],
                ['filename' => '/portrait.jpg', 'width' => 800, 'height' => 1200],
                ['filename' => '/large-square.jpg', 'width' => 1000, 'height' => 1000],
            ])->each(fn ($data) => createImage($data));
        });

        test('counts images correctly', function () {
            expect(Image::count())->toBe(5);
        });

        test('filters by minimum dimensions', function () {
            expect(Image::byDimensions(800, 600)->get())->toHaveCount(4)
                ->and(Image::byDimensions(1000, 1000)->get())->toHaveCount(3);
        });

        test('organizes by aspect ratio', function () {
            expect(Image::byAspectRatio('landscape')->get())->toHaveCount(2)
                ->and(Image::byAspectRatio('portrait')->get())->toHaveCount(1)
                ->and(Image::byAspectRatio('square')->get())->toHaveCount(2);
        });
    });

    describe('File Validation', function () {
        test('validates supported image extensions', function () {
            collect(['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'])
                ->each(function ($ext) {
                    $image = createImage(['filename' => "/test.{$ext}"]);

                    expect($image->extension)->toBe($ext);
                });
        });
    });

    describe('Search and Filtering', function () {
        beforeEach(function () {
            collect([
                ['filename' => '/nature/sunset.jpg', 'alt' => 'Beautiful sunset'],
                ['filename' => '/nature/forest.jpg', 'alt' => 'Dense forest'],
                ['filename' => '/city/skyline.jpg', 'alt' => 'City skyline'],
                ['filename' => '/portrait/person.jpg', 'alt' => 'Portrait photo'],
            ])->each(fn ($data) => createImage($data + ['width' => 1920, 'height' => 1080]));
        });

        test('filters by path pattern', function () {
            expect(Image::where('filename', 'like', '/nature/%')->get())->toHaveCount(2);
        });

        test('searches by alt text', function () {
            expect(Image::where('alt', 'like', '%sunset%')->get())->toHaveCount(1);
        });

        test('combines dimension and aspect ratio filters', function () {
            expect(Image::byDimensions(1500, 900)->byAspectRatio('landscape')->get())->toHaveCount(4);
        });
    });
});
