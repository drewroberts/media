<?php

use DrewRoberts\Media\Models\Image;

beforeEach(function () {
    $this->user = createUser();
});

describe('Image Model', function () {
    describe('Basic Creation', function () {
        test('creates images with all attributes', function () {
            $image = createImage(sampleImageData());

            expect($image)
                ->toBeInstanceOf(Image::class)
                ->filename->toBe('/sample-image.jpg')
                ->width->toBe(1920)
                ->height->toBe(1080)
                ->description->toBe('Sample image')
                ->alt->toBe('Sample alt text')
                ->credit->toBe('Sample Photographer');
        });

        test('handles nullable fields gracefully', function () {
            $image = createImage(['description' => null, 'alt' => null, 'credit' => null]);

            expect($image)
                ->description->toBeNull()
                ->alt->toBeNull()
                ->credit->toBeNull();
        });

        test('enforces unique filenames', function () {
            createImage(['filename' => '/unique-test.jpg']);

            expect(fn () => createImage(['filename' => '/unique-test.jpg']))
                ->toThrow(\Illuminate\Database\QueryException::class);
        });
    });

    describe('Authentication Tracking', function () {
        test('sets creator_id when authenticated', function () {
            $user = authenticateUser();

            $image = createImage();

            expect($image)->toHaveCreator($user->id);
        });

        test('sets updater_id when saving changes', function () {
            $user = authenticateUser();
            $image = createImage();

            $image->update(['alt' => 'Updated alt text']);

            expect($image)->toHaveUpdater($user->id);
        });

        test('maintains creator relationship', function () {
            $user = authenticateUser();
            $image = createImage();

            expect($image->creator)
                ->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
                ->id->toBe($user->id);
        });

        test('maintains updater relationship', function () {
            $user = authenticateUser();
            $image = createImage();
            $image->update(['description' => 'Updated description']);

            expect($image->updater)
                ->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
                ->id->toBe($user->id);
        });
    });

    describe('Data Types', function () {
        test('casts dimensions as integers', function () {
            $image = createImage(['width' => '800', 'height' => '600']);

            expect($image)
                ->width->toBeInt()->toBe(800)
                ->height->toBeInt()->toBe(600);
        });
    });

    describe('Model Configuration', function () {
        test('uses factory trait', function () {
            expect(in_array(\Illuminate\Database\Eloquent\Factories\HasFactory::class, class_uses(Image::class)))
                ->toBeTrue();
        });

        test('guards id field', function () {
            expect((new Image)->getGuarded())->toBe(['id']);
        });
    });

    describe('Multiple Images', function () {
        test('handles multiple images with unique filenames', function () {
            $image1 = createImage(['filename' => '/image1.jpg']);
            $image2 = createImage(['filename' => '/image2.jpg']);

            expect($image1->filename)->toBe('/image1.jpg')
                ->and($image2->filename)->toBe('/image2.jpg')
                ->and($image1->filename)->not->toBe($image2->filename);
        });
    });
});
