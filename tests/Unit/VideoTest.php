<?php

use DrewRoberts\Media\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    // Create a test user for the relationships
    $this->user = \Illuminate\Foundation\Auth\User::factory()->create();
});

it('can create a video', function () {
    $video = Video::create([
        'filename' => '/test-video.mp4',
        'duration' => 120,
        'width' => 1920,
        'height' => 1080,
        'description' => 'Test video',
        'credit' => 'Test videographer',
    ]);

    expect($video)->toBeInstanceOf(Video::class)
        ->and($video->filename)->toBe('/test-video.mp4')
        ->and($video->duration)->toBe(120)
        ->and($video->width)->toBe(1920)
        ->and($video->height)->toBe(1080)
        ->and($video->description)->toBe('Test video')
        ->and($video->credit)->toBe('Test videographer');
});

it('automatically sets creator_id when authenticated', function () {
    Auth::login($this->user);

    $video = Video::create([
        'filename' => '/creator-test.mp4',
        'duration' => 60,
        'width' => 800,
        'height' => 600,
    ]);

    expect($video->creator_id)->toBe($this->user->id);
});

it('automatically sets updater_id when saving while authenticated', function () {
    Auth::login($this->user);

    $video = Video::create([
        'filename' => '/updater-test.mp4',
        'duration' => 90,
        'width' => 800,
        'height' => 600,
    ]);

    $video->description = 'Updated description';
    $video->save();

    expect($video->updater_id)->toBe($this->user->id);
});

it('has creator relationship', function () {
    Auth::login($this->user);

    $video = Video::create([
        'filename' => '/relationship-test.mp4',
        'duration' => 45,
        'width' => 800,
        'height' => 600,
    ]);

    expect($video->creator)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
        ->and($video->creator->id)->toBe($this->user->id);
});

it('has updater relationship', function () {
    Auth::login($this->user);

    $video = Video::create([
        'filename' => '/updater-relationship.mp4',
        'duration' => 75,
        'width' => 800,
        'height' => 600,
    ]);

    $video->update(['description' => 'Updated description']);

    expect($video->updater)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
        ->and($video->updater->id)->toBe($this->user->id);
});

it('can handle nullable fields', function () {
    $video = Video::create([
        'filename' => '/minimal-video.mp4',
        'duration' => 30,
        'width' => 400,
        'height' => 300,
    ]);

    expect($video->description)->toBeNull()
        ->and($video->credit)->toBeNull();
});

it('has duration width and height as integers', function () {
    $video = Video::create([
        'filename' => '/integer-test.mp4',
        'duration' => '120',
        'width' => '800',
        'height' => '600',
    ]);

    expect($video->duration)->toBeInt()
        ->and($video->width)->toBeInt()
        ->and($video->height)->toBeInt()
        ->and($video->duration)->toBe(120)
        ->and($video->width)->toBe(800)
        ->and($video->height)->toBe(600);
});

it('enforces unique filename', function () {
    Video::create([
        'filename' => '/unique-test.mp4',
        'duration' => 60,
        'width' => 800,
        'height' => 600,
    ]);

    expect(fn() => Video::create([
        'filename' => '/unique-test.mp4',
        'duration' => 90,
        'width' => 400,
        'height' => 300,
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

it('uses factory', function () {
    expect(in_array(\Illuminate\Database\Eloquent\Factories\HasFactory::class, class_uses(Video::class)))
        ->toBeTrue();
});

it('has guarded id field', function () {
    $video = new Video();
    
    expect($video->getGuarded())->toBe(['id']);
});

it('can create multiple videos with different filenames', function () {
    $video1 = Video::create(['filename' => '/video1.mp4', 'duration' => 60, 'width' => 800, 'height' => 600]);
    $video2 = Video::create(['filename' => '/video2.mp4', 'duration' => 120, 'width' => 1200, 'height' => 800]);

    expect($video1->filename)->toBe('/video1.mp4')
        ->and($video2->filename)->toBe('/video2.mp4')
        ->and($video1->filename)->not->toBe($video2->filename);
});