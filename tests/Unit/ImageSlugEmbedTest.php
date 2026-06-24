<?php

use DrewRoberts\Media\Models\Image;

beforeEach(function () {
    $this->user = createUser();
});

describe('Image Slug', function () {
    describe('Basic Slug Functionality', function () {
        test('creates image with slug', function () {
            $image = createImage(['slug' => 'test-image-slug']);

            expect($image)
                ->toBeInstanceOf(Image::class)
                ->slug->toBe('test-image-slug');
        });

        test('creates image without slug', function () {
            $image = createImage(['slug' => null]);

            expect($image)
                ->slug->toBeNull();
        });

        test('enforces unique slugs', function () {
            createImage(['filename' => '/unique1.jpg', 'slug' => 'unique-slug']);

            expect(fn () => createImage(['filename' => '/unique2.jpg', 'slug' => 'unique-slug']))
                ->toThrow(\Illuminate\Database\QueryException::class);
        });

        test('allows null slugs for multiple images', function () {
            $image1 = createImage(['filename' => '/image1.jpg', 'slug' => null]);
            $image2 = createImage(['filename' => '/image2.jpg', 'slug' => null]);

            expect($image1->slug)->toBeNull()
                ->and($image2->slug)->toBeNull();
        });
    });

    describe('Slug Queries', function () {
        test('can find image by slug', function () {
            $image = createImage(['slug' => 'findable-slug']);

            $found = Image::where('slug', 'findable-slug')->first();

            expect($found)
                ->toBeInstanceOf(Image::class)
                ->id->toBe($image->id)
                ->slug->toBe('findable-slug');
        });

        test('returns null when slug does not exist', function () {
            $found = Image::where('slug', 'non-existent-slug')->first();

            expect($found)->toBeNull();
        });
    });
});

describe('Image Embed', function () {
    describe('Embed Method', function () {
        test('returns formatted embed string with slug', function () {
            $image = createImage(['slug' => 'my-image-slug']);

            expect($image->embed())
                ->toBe('{image:my-image-slug}');
        });

        test('returns empty string when slug is null', function () {
            $image = createImage(['slug' => null]);

            expect($image->embed())
                ->toBe('');
        });

        test('returns empty string when slug is empty', function () {
            $image = createImage(['slug' => '']);

            expect($image->embed())
                ->toBe('');
        });

        test('handles slug with special characters', function () {
            $image = createImage(['slug' => 'image-with-123']);

            expect($image->embed())
                ->toBe('{image:image-with-123}');
        });

        test('handles slug with hyphens', function () {
            $image = createImage(['slug' => 'my-awesome-image']);

            expect($image->embed())
                ->toBe('{image:my-awesome-image}');
        });
    });

    describe('Embed Integration', function () {
        test('embed works after updating slug', function () {
            $image = createImage(['slug' => 'original-slug']);
            $image->update(['slug' => 'updated-slug']);

            expect($image->embed())
                ->toBe('{image:updated-slug}');
        });

        test('multiple images have different embed strings', function () {
            $image1 = createImage(['filename' => '/image1.jpg', 'slug' => 'first-image']);
            $image2 = createImage(['filename' => '/image2.jpg', 'slug' => 'second-image']);

            expect($image1->embed())
                ->toBe('{image:first-image}')
                ->and($image2->embed())
                ->toBe('{image:second-image}')
                ->and($image1->embed())
                ->not->toBe($image2->embed());
        });
    });
});
