<?php

use DrewRoberts\Media\Support\YouTube\YouTubeService;
use Illuminate\Support\Facades\Http;

it('normalizes a YouTube API video item into VideoData', function () {
    $service = new YouTubeService('fake-key');

    $item = [
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

    $data = $service->normalize($item);

    expect($data->id)->toBe('abc123xyz')
        ->and($data->title)->toBe('Sample Title')
        ->and($data->description)->toBe('Sample Description')
        ->and($data->channelTitle)->toBe('Channel Name')
        ->and($data->durationSeconds)->toBe(3723)
        ->and($data->publishedAt?->format('c'))->toBe('2024-07-10T12:34:56+00:00')
        ->and($data->viewCount)->toBe(12345)
        ->and($data->likeCount)->toBe(678)
        ->and($data->commentCount)->toBe(90)
        ->and($data->privacyStatus)->toBe('public')
        ->and($data->embeddable)->toBeTrue()
        ->and($data->broadcast)->toBe('live')
        ->and($data->thumbnailUrl)->toBe('https://i.ytimg.com/vi/abc123xyz/hqdefault.jpg');
});

it('sets broadcast to upcoming when only scheduledStartTime is present', function () {
    $service = new YouTubeService('fake-key');

    $item = [
        'id' => 'vid2',
        'snippet' => [
            'title' => 'Upcoming Live',
            'thumbnails' => [
                'standard' => ['url' => 'https://i.ytimg.com/vi/vid2/sddefault.jpg'],
            ],
        ],
        'contentDetails' => ['duration' => 'PT5M'],
        'liveStreamingDetails' => ['scheduledStartTime' => '2025-01-01T00:00:00Z'],
    ];

    $data = $service->normalize($item);

    expect($data->broadcast)->toBe('upcoming')
        ->and($data->durationSeconds)->toBe(300)
        ->and($data->thumbnailUrl)->toBe('https://i.ytimg.com/vi/vid2/sddefault.jpg');
});

it('picks best thumbnail using preference order and can fetch via service->fetch()', function () {
    config()->set('media.youtube.api_key', 'test-key');

    Http::fake([
        'https://www.googleapis.com/youtube/v3/videos*' => Http::response([
            'items' => [[
                'id' => 'bestthumb',
                'snippet' => [
                    'title' => 'T',
                    'thumbnails' => [
                        'medium' => ['url' => 'https://i.ytimg.com/vi/bestthumb/mqdefault.jpg'],
                        'maxres' => ['url' => 'https://i.ytimg.com/vi/bestthumb/maxresdefault.jpg'],
                    ],
                ],
                'contentDetails' => ['duration' => 'PT10S'],
                'statistics' => ['viewCount' => '1'],
                'status' => ['privacyStatus' => 'public', 'embeddable' => true],
            ]],
        ], 200),
    ]);

    $service = app(YouTubeService::class);
    $data = $service->fetch('bestthumb');

    expect($data->thumbnailUrl)->toBe('https://i.ytimg.com/vi/bestthumb/maxresdefault.jpg')
        ->and($data->durationSeconds)->toBe(10);

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '/videos')
            && $request->method() === 'GET'
            && $request->data()['id'] === 'bestthumb';
    });
});
