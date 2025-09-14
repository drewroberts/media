<?php

use DrewRoberts\Media\Facades\YouTube;
use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Support\YouTube\YouTubeService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->user = createUser();
});

describe('Video Management', function () {
    describe('Lifecycle Operations', function () {
        test('creates videos with proper creator tracking', function () {
            $user = authenticateUser();
            
            $video = createVideo(sampleVideoData());

            expect($video)
                ->toHaveCreator($user->id)
                ->youtubeUrl()->toBeYouTubeUrl('sampleVideoId');
        });

        test('updates videos with proper updater tracking', function () {
            $user = authenticateUser();
            $video = createVideo();

            $video->update(['description' => 'Updated description']);

            expect($video->fresh())
                ->toHaveUpdater($user->id)
                ->description->toBe('Updated description');
        });
    });

    describe('YouTube Integration', function () {
        test('parses youtube IDs from various URL formats', function () {
            expect(YouTube::parseId('https://www.youtube.com/watch?v=VHpxrjqIJDc'))->toBe('VHpxrjqIJDc')
                ->and(YouTube::parseId('https://youtu.be/VHpxrjqIJDc'))->toBe('VHpxrjqIJDc')
                ->and(YouTube::parseId('VHpxrjqIJDc'))->toBe('VHpxrjqIJDc');
        });

        test('fetches and normalizes video data from API', function () {
            Http::fake(['*/videos*' => Http::response(youtubeApiResponse(), 200)]);
            YouTube::swap(new YouTubeService('test-key'));
            
            $data = YouTube::fetch('VHpxrjqIJDc');

            expect($data)
                ->id->toBe('VHpxrjqIJDc')
                ->title->toBe('API Title')
                ->description->toBe('API Description')
                ->channelTitle->toBe('API Channel')
                ->durationSeconds->toBe(120)
                ->privacyStatus->toBe('public')
                ->embeddable->toBeTrue();
        });

        test('preserves custom description during API refresh', function () {
            $user = authenticateUser();
            
            $video = createVideo([
                'title' => 'Old Title',
                'description' => 'Keep this description',
                'duration' => 10,
                'view_count' => 1,
                'privacy' => 'unlisted',
            ]);

            Http::fake([
                '*/videos*' => Http::response(youtubeApiResponse([
                    'items' => [[
                        'id' => 'VHpxrjqIJDc',
                        'snippet' => [
                            'title' => 'New Title',
                            'description' => 'New Description (should not overwrite)',
                        ],
                        'contentDetails' => ['duration' => 'PT2M10S'],
                        'statistics' => ['viewCount' => '200'],
                        'status' => ['privacyStatus' => 'private'],
                    ]],
                ]), 200),
            ]);

            YouTube::swap(new YouTubeService('test-key'));
            $data = YouTube::fetch($video->identifier);

            $video->update([
                'title' => $data->title,
                'duration' => $data->durationSeconds,
                'view_count' => $data->viewCount,
                'privacy' => $data->privacyStatus,
            ]);

            expect($video->fresh())
                ->title->toBe('New Title')
                ->description->toBe('Keep this description') // Preserved
                ->duration->toBe(130)
                ->view_count->toBe(200)
                ->privacy->toBe('private');
        });
    });
});
