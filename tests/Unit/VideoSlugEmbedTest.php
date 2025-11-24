<?php

use DrewRoberts\Media\Models\Video;

beforeEach(function () {
    $this->user = createUser();
});

describe('Video Slug', function () {
    describe('Basic Slug Functionality', function () {
        test('creates video with slug', function () {
            $video = createVideo(['slug' => 'test-video-slug']);

            expect($video)
                ->toBeInstanceOf(Video::class)
                ->slug->toBe('test-video-slug');
        });

        test('creates video without slug', function () {
            $video = createVideo(['slug' => null]);

            expect($video)
                ->slug->toBeNull();
        });

        test('enforces unique slugs', function () {
            createVideo(['identifier' => 'unique-vid-1', 'slug' => 'unique-slug']);

            expect(fn () => createVideo(['identifier' => 'unique-vid-2', 'slug' => 'unique-slug']))
                ->toThrow(\Illuminate\Database\QueryException::class);
        });

        test('allows null slugs for multiple videos', function () {
            $video1 = createVideo(['identifier' => 'vid1', 'slug' => null]);
            $video2 = createVideo(['identifier' => 'vid2', 'slug' => null]);

            expect($video1->slug)->toBeNull()
                ->and($video2->slug)->toBeNull();
        });
    });

    describe('Slug Queries', function () {
        test('can find video by slug', function () {
            $video = createVideo(['slug' => 'findable-video-slug']);

            $found = Video::where('slug', 'findable-video-slug')->first();

            expect($found)
                ->toBeInstanceOf(Video::class)
                ->id->toBe($video->id)
                ->slug->toBe('findable-video-slug');
        });

        test('returns null when slug does not exist', function () {
            $found = Video::where('slug', 'non-existent-slug')->first();

            expect($found)->toBeNull();
        });
    });
});

describe('Video Embed', function () {
    describe('Embed Method', function () {
        test('returns formatted embed string with slug', function () {
            $video = createVideo(['slug' => 'my-video-slug']);

            expect($video->embed())
                ->toBe('{video:my-video-slug}');
        });

        test('returns empty string when slug is null', function () {
            $video = createVideo(['slug' => null]);

            expect($video->embed())
                ->toBe('');
        });

        test('returns empty string when slug is empty', function () {
            $video = createVideo(['slug' => '']);

            expect($video->embed())
                ->toBe('');
        });

        test('handles slug with special characters', function () {
            $video = createVideo(['slug' => 'video-with-123']);

            expect($video->embed())
                ->toBe('{video:video-with-123}');
        });

        test('handles slug with hyphens', function () {
            $video = createVideo(['slug' => 'my-awesome-video']);

            expect($video->embed())
                ->toBe('{video:my-awesome-video}');
        });
    });

    describe('Embed Integration', function () {
        test('embed works after updating slug', function () {
            $video = createVideo(['slug' => 'original-slug']);
            $video->update(['slug' => 'updated-slug']);

            expect($video->embed())
                ->toBe('{video:updated-slug}');
        });

        test('multiple videos have different embed strings', function () {
            $video1 = createVideo(['identifier' => 'vid1', 'slug' => 'first-video']);
            $video2 = createVideo(['identifier' => 'vid2', 'slug' => 'second-video']);

            expect($video1->embed())
                ->toBe('{video:first-video}')
                ->and($video2->embed())
                ->toBe('{video:second-video}')
                ->and($video1->embed())
                ->not->toBe($video2->embed());
        });

        test('embed is independent of youtube url', function () {
            $video = createVideo(['identifier' => 'dQw4w9WgXcQ', 'slug' => 'rick-roll']);

            expect($video->embed())
                ->toBe('{video:rick-roll}')
                ->and($video->youtubeUrl())
                ->toBe('https://youtu.be/dQw4w9WgXcQ');
        });
    });
});
