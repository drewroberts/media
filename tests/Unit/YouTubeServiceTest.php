<?php

use DrewRoberts\Media\Support\YouTube\YouTubeService;
use Illuminate\Support\Facades\Http;

describe('YouTube Service', function () {
    describe('API Response Normalization', function () {
        test('converts YouTube API video item to VideoData object', function () {
            $service = new YouTubeService('fake-key');

            $apiItem = [
                'id' => 'abc123xyz',
                'snippet' => [
                    'title' => 'Sample Title',
                    'description' => 'Sample Description',
                    'channelTitle' => 'Channel Name',
                    'publishedAt' => '2024-07-10T12:34:56Z',
                    'thumbnails' => [
                        'high' => ['url' => 'https://i.ytimg.com/vi/abc123xyz/hqdefault.jpg'],
                    ],
                ],
                'contentDetails' => [
                    'duration' => 'PT1H2M3S',
                ],
                'statistics' => [
                    'viewCount' => '12345',
                    'likeCount' => '678',
                    'commentCount' => '90',
                ],
                'status' => [
                    'privacyStatus' => 'public',
                    'embeddable' => true,
                ],
                'liveStreamingDetails' => [
                    'actualStartTime' => '2024-07-10T12:35:00Z',
                ],
            ];

            $data = $service->normalize($apiItem);

            expect($data)
                ->id->toBe('abc123xyz')
                ->title->toBe('Sample Title')
                ->description->toBe('Sample Description')
                ->channelTitle->toBe('Channel Name')
                ->durationSeconds->toBe(3723)
                ->viewCount->toBe(12345)
                ->likeCount->toBe(678)
                ->commentCount->toBe(90)
                ->privacyStatus->toBe('public')
                ->embeddable->toBeTrue()
                ->broadcast->toBe('live')
                ->thumbnailUrl->toBe('https://i.ytimg.com/vi/abc123xyz/hqdefault.jpg');

            expect($data->publishedAt?->format('c'))->toBe('2024-07-10T12:34:56+00:00');
        });

        test('handles upcoming live stream broadcasts', function () {
            $service = new YouTubeService('fake-key');

            $apiItem = [
                'id' => 'upcoming-video',
                'snippet' => [
                    'title' => 'Upcoming Live Stream',
                    'thumbnails' => [
                        'standard' => ['url' => 'https://i.ytimg.com/vi/upcoming-video/sddefault.jpg'],
                    ],
                ],
                'contentDetails' => ['duration' => 'PT5M'],
                'liveStreamingDetails' => ['scheduledStartTime' => '2025-01-01T00:00:00Z'],
            ];

            $data = $service->normalize($apiItem);

            expect($data)
                ->broadcast->toBe('upcoming')
                ->durationSeconds->toBe(300)
                ->thumbnailUrl->toBe('https://i.ytimg.com/vi/upcoming-video/sddefault.jpg');
        });
    });

    describe('API Integration', function () {
        test('fetches video data and selects best thumbnail', function () {
            config()->set('media.youtube.api_key', 'test-key');

            Http::fake([
                'https://www.googleapis.com/youtube/v3/videos*' => Http::response([
                    'items' => [[
                        'id' => 'best-thumb-video',
                        'snippet' => [
                            'title' => 'Video Title',
                            'thumbnails' => [
                                'medium' => ['url' => 'https://i.ytimg.com/vi/best-thumb-video/mqdefault.jpg'],
                                'maxres' => ['url' => 'https://i.ytimg.com/vi/best-thumb-video/maxresdefault.jpg'],
                            ],
                        ],
                        'contentDetails' => ['duration' => 'PT10S'],
                        'statistics' => ['viewCount' => '1'],
                        'status' => ['privacyStatus' => 'public', 'embeddable' => true],
                    ]],
                ], 200),
            ]);

            $service = app(YouTubeService::class);
            $data = $service->fetch('best-thumb-video');

            expect($data)
                ->thumbnailUrl->toBe('https://i.ytimg.com/vi/best-thumb-video/maxresdefault.jpg')
                ->durationSeconds->toBe(10);

            Http::assertSent(function ($request) {
                return str_contains($request->url(), '/videos')
                    && $request->method() === 'GET'
                    && $request->data()['id'] === 'best-thumb-video';
            });
        });
    });
});
