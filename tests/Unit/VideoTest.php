<?php

use DrewRoberts\Media\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    // Create a test user for the relationships
    $this->user = \DrewRoberts\Media\Tests\TestUser::factory()->create();
});

it('can create a video', function () {
    $video = Video::create([
        'identifier' => 'vmFLvGFHRBM',
        'duration' => 120,
        'width' => 1920,
        'height' => 1080,
        'description' => 'Test video',
        'credit' => 'Test videographer',
    ]);

    expect($video)->toBeInstanceOf(Video::class)
        ->and($video->identifier)->toBe('vmFLvGFHRBM')
        ->and($video->duration)->toBe(120)
        ->and($video->width)->toBe(1920)
        ->and($video->height)->toBe(1080)
        ->and($video->description)->toBe('Test video')
        ->and($video->credit)->toBe('Test videographer');
});

it('automatically sets creator_id when authenticated', function () {
    Auth::login($this->user);

    $video = Video::create([
        'identifier' => '/creator-test.mp4',
        'duration' => 60,
        'width' => 800,
        'height' => 600,
    ]);

    expect($video->creator_id)->toBe($this->user->id);
});

it('automatically sets updater_id when saving while authenticated', function () {
    Auth::login($this->user);

    $video = Video::create([
        'identifier' => 'vmFLvGFHRBM',
        'duration' => 90,
    ]);

    $video->description = 'Updated description';
    $video->save();

    expect($video->updater_id)->toBe($this->user->id);
});

it('has creator relationship', function () {
    Auth::login($this->user);

    $video = Video::create([
        'identifier' => '/relationship-test.mp4',
        'duration' => 45,
    ]);

    expect($video->creator)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
        ->and($video->creator->id)->toBe($this->user->id);
});

it('has updater relationship', function () {
    Auth::login($this->user);

    $video = Video::create([
        'identifier' => '/updater-relationship.mp4',
        'duration' => 75,
    ]);

    $video->update(['description' => 'Updated description']);

    expect($video->updater)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
        ->and($video->updater->id)->toBe($this->user->id);
});

it('can handle nullable fields', function () {
    $video = Video::create([
        'identifier' => '/minimal-video.mp4',
        'duration' => 3,
    ]);

    expect($video->description)->toBeNull()
        ->and($video->credit)->toBeNull();
});

it('has duration as integer', function () {
    $video = Video::create([
        'identifier' => '/integer-test.mp4',
        'duration' => '120',
    ]);

    expect($video->duration)->toBeInt()
        ->and($video->duration)->toBe(120);
});

it('enforces unique identifier', function () {
    Video::create([
        'identifier' => '/unique-test.mp4',
        'duration' => 60,
    ]);

    expect(fn () => Video::create([
        'identifier' => '/unique-test.mp4',
        'duration' => 9,
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

it('uses factory', function () {
    expect(in_array(\Illuminate\Database\Eloquent\Factories\HasFactory::class, class_uses(Video::class)))
        ->toBeTrue();
});

it('has guarded id field', function () {
    $video = new Video;

    expect($video->getGuarded())->toBe(['id']);
});

it('can create multiple videos with different identifiers', function () {
    $video1 = Video::create(['identifier' => '/video1.mp4', 'duration' => 60]);
    $video2 = Video::create(['identifier' => '/video2.mp4', 'duration' => 120]);

    expect($video1->identifier)->toBe('/video1.mp4')
        ->and($video2->identifier)->toBe('/video2.mp4')
        ->and($video1->identifier)->not->toBe($video2->identifier);
});
