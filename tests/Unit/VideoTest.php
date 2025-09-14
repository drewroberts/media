<?php

use DrewRoberts\Media\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $this->user = createUser();
});

describe('Video Model', function () {
    describe('Basic Creation', function () {
        test('creates videos with all attributes', function () {
            $video = Video::create(sampleVideoData());

            expect($video)
                ->toBeInstanceOf(Video::class)
                ->identifier->toBe('sampleVideoId')
                ->source->toBe('youtube')
                ->title->toBe('Sample Video')
                ->description->toBe('Sample description')
                ->duration->toBe(120);
        });

        test('handles nullable fields gracefully', function () {
            $video = createVideo(['description' => null, 'credit' => null]);

            expect($video)
                ->description->toBeNull()
                ->credit->toBeNull();
        });

        test('enforces unique identifiers', function () {
            createVideo(['identifier' => 'unique-video']);

            expect(fn () => createVideo(['identifier' => 'unique-video']))
                ->toThrow(\Illuminate\Database\QueryException::class);
        });
    });

    describe('Authentication Tracking', function () {
        test('sets creator_id when authenticated', function () {
            $user = authenticateUser();

            $video = createVideo();

            expect($video)->toHaveCreator($user->id);
        });

        test('sets updater_id when saving changes', function () {
            $user = authenticateUser();
            $video = createVideo();

            $video->update(['description' => 'Updated description']);

            expect($video)->toHaveUpdater($user->id);
        });

        test('maintains creator relationship', function () {
            $user = authenticateUser();
            $video = createVideo();

            expect($video->creator)
                ->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
                ->id->toBe($user->id);
        });

        test('maintains updater relationship', function () {
            $user = authenticateUser();
            $video = createVideo();
            $video->update(['title' => 'Updated Title']);

            expect($video->updater)
                ->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
                ->id->toBe($user->id);
        });
    });

    describe('Data Types', function () {
        test('casts duration as integer', function () {
            $video = createVideo(['duration' => '300']);

            expect($video)
                ->duration->toBeInt()->toBe(300);
        });
    });

    describe('Model Configuration', function () {
        test('uses factory trait', function () {
            expect(in_array(\Illuminate\Database\Eloquent\Factories\HasFactory::class, class_uses(Video::class)))
                ->toBeTrue();
        });

        test('guards id field', function () {
            $video = new Video;

            expect($video->getGuarded())->toBe(['id']);
        });
    });

    describe('Multiple Videos', function () {
        test('handles multiple videos with unique identifiers', function () {
            $video1 = createVideo(['identifier' => 'video-1']);
            $video2 = createVideo(['identifier' => 'video-2']);

            expect($video1->identifier)->toBe('video-1')
                ->and($video2->identifier)->toBe('video-2')
                ->and($video1->identifier)->not->toBe($video2->identifier);
        });
    });
});
