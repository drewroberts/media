<?php

use DrewRoberts\Media\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    // Create a test user for the relationships
    $this->user = \Illuminate\Foundation\Auth\User::factory()->create();
});

it('can create an image', function () {
    $image = Image::create([
        'filename' => '/test-image.jpg',
        'width' => 1920,
        'height' => 1080,
        'description' => 'Test image',
        'alt' => 'Test alt text',
        'credit' => 'Test photographer',
    ]);

    expect($image)->toBeInstanceOf(Image::class)
        ->and($image->filename)->toBe('/test-image.jpg')
        ->and($image->width)->toBe(1920)
        ->and($image->height)->toBe(1080)
        ->and($image->description)->toBe('Test image')
        ->and($image->alt)->toBe('Test alt text')
        ->and($image->credit)->toBe('Test photographer');
});

it('automatically sets creator_id when authenticated', function () {
    Auth::login($this->user);

    $image = Image::create([
        'filename' => '/creator-test.jpg',
        'width' => 800,
        'height' => 600,
    ]);

    expect($image->creator_id)->toBe($this->user->id);
});

it('automatically sets updater_id when saving while authenticated', function () {
    Auth::login($this->user);

    $image = Image::create([
        'filename' => '/updater-test.jpg',
        'width' => 800,
        'height' => 600,
    ]);

    $image->alt = 'Updated alt text';
    $image->save();

    expect($image->updater_id)->toBe($this->user->id);
});

it('has creator relationship', function () {
    Auth::login($this->user);

    $image = Image::create([
        'filename' => '/relationship-test.jpg',
        'width' => 800,
        'height' => 600,
    ]);

    expect($image->creator)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
        ->and($image->creator->id)->toBe($this->user->id);
});

it('has updater relationship', function () {
    Auth::login($this->user);

    $image = Image::create([
        'filename' => '/updater-relationship.jpg',
        'width' => 800,
        'height' => 600,
    ]);

    $image->update(['description' => 'Updated description']);

    expect($image->updater)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
        ->and($image->updater->id)->toBe($this->user->id);
});

it('can handle nullable fields', function () {
    $image = Image::create([
        'filename' => '/minimal-image.jpg',
        'width' => 400,
        'height' => 300,
    ]);

    expect($image->description)->toBeNull()
        ->and($image->alt)->toBeNull()
        ->and($image->credit)->toBeNull();
});

it('has width and height as integers', function () {
    $image = Image::create([
        'filename' => '/integer-test.jpg',
        'width' => '800',
        'height' => '600',
    ]);

    expect($image->width)->toBeInt()
        ->and($image->height)->toBeInt()
        ->and($image->width)->toBe(800)
        ->and($image->height)->toBe(600);
});

it('enforces unique filename', function () {
    Image::create([
        'filename' => '/unique-test.jpg',
        'width' => 800,
        'height' => 600,
    ]);

    expect(fn () => Image::create([
        'filename' => '/unique-test.jpg',
        'width' => 400,
        'height' => 300,
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

it('uses factory', function () {
    expect(in_array(\Illuminate\Database\Eloquent\Factories\HasFactory::class, class_uses(Image::class)))
        ->toBeTrue();
});

it('has guarded id field', function () {
    $image = new Image;

    expect($image->getGuarded())->toBe(['id']);
});

it('can create multiple images with different filenames', function () {
    $image1 = Image::create(['filename' => '/image1.jpg', 'width' => 800, 'height' => 600]);
    $image2 = Image::create(['filename' => '/image2.jpg', 'width' => 1200, 'height' => 800]);

    expect($image1->filename)->toBe('/image1.jpg')
        ->and($image2->filename)->toBe('/image2.jpg')
        ->and($image1->filename)->not->toBe($image2->filename);
});
