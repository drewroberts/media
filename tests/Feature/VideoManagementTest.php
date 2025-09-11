<?php

use DrewRoberts\Media\Facades\YouTube;
use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Support\YouTube\YouTubeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = \DrewRoberts\Media\Tests\TestUser::factory()->create();
});

it('handles video lifecycle and generates youtube short link', function () {
    Auth::login($this->user);

    $video = Video::create([
        'identifier' => 'VHpxrjqIJDc',
        'source' => 'youtube',
        'title' => 'My Test Video',
        'description' => 'Initial description',
    ]);

    expect($video->creator_id)->toBe($this->user->id)
        ->and($video->youtubeUrl())->toBe('https://youtu.be/VHpxrjqIJDc');

    $video->update(['description' => 'Updated description']);
    $video->refresh();

    expect($video->updater_id)->toBe($this->user->id)
        ->and($video->description)->toBe('Updated description');
});

it('parses youtube ids and normalizes fetched data', function () {
    // parseId cases
    expect(YouTube::parseId('https://www.youtube.com/watch?v=VHpxrjqIJDc'))->toBe('VHpxrjqIJDc')
        ->and(YouTube::parseId('https://youtu.be/VHpxrjqIJDc'))->toBe('VHpxrjqIJDc')
        ->and(YouTube::parseId('VHpxrjqIJDc'))->toBe('VHpxrjqIJDc');

    // Fake YouTube API response
    Http::fake([
        '*/videos*' => Http::response([
            'items' => [[
                'id' => 'VHpxrjqIJDc',
                'snippet' => [
                    'title' => 'Fetched Title',
                    'description' => 'Fetched Description',
                    'channelTitle' => 'Channel Name',
                    'publishedAt' => '2024-01-02T03:04:05Z',
                    'thumbnails' => [
                        'maxres' => ['url' => 'https://i.ytimg.com/maxresdefault.jpg'],
                    ],
                ],
                'contentDetails' => [
                    'duration' => 'PT1H2M3S',
                ],
                'statistics' => [
                    'viewCount' => '123',
                    'likeCount' => '45',
                    'commentCount' => '6',
                ],
                'status' => [
                    'privacyStatus' => 'public',
                    'embeddable' => true,
                ],
                'liveStreamingDetails' => [
                    'scheduledStartTime' => null,
                    'actualStartTime' => null,
                ],
            ]],
        ], 200),
    ]);

    // Ensure service has an API key for test context
    YouTube::swap(new YouTubeService('test-key'));
    $data = YouTube::fetch('VHpxrjqIJDc');

    expect($data->id)->toBe('VHpxrjqIJDc')
        ->and($data->title)->toBe('Fetched Title')
        ->and($data->description)->toBe('Fetched Description')
        ->and($data->channelTitle)->toBe('Channel Name')
        ->and($data->durationSeconds)->toBe(3723)
        ->and($data->privacyStatus)->toBe('public')
        ->and($data->embeddable)->toBeTrue();
});

it('simulates refresh from API while preserving editable description', function () {
    Auth::login($this->user);

    $video = Video::create([
        'identifier' => 'VHpxrjqIJDc',
        'source' => 'youtube',
        'title' => 'Old Title',
        'description' => 'Keep this description',
        'duration' => 10,
        'view_count' => 1,
        'like_count' => 1,
        'comment_count' => 0,
        'privacy' => 'unlisted',
        'embeddable' => true,
        'broadcast' => 'none',
    ]);

    Http::fake([
        '*/videos*' => Http::response([
            'items' => [[
                'id' => 'VHpxrjqIJDc',
                'snippet' => [
                    'title' => 'New Title',
                    'description' => 'New Description (should not overwrite)',
                    'channelTitle' => 'New Channel',
                    'publishedAt' => '2024-02-02T00:00:00Z',
                    'thumbnails' => [
                        'maxres' => ['url' => 'https://i.ytimg.com/maxresdefault.jpg'],
                    ],
                ],
                'contentDetails' => [
                    'duration' => 'PT2M10S',
                ],
                'statistics' => [
                    'viewCount' => '200',
                    'likeCount' => '20',
                    'commentCount' => '2',
                ],
                'status' => [
                    'privacyStatus' => 'private',
                    'embeddable' => true,
                ],
                'liveStreamingDetails' => [],
            ]],
        ], 200),
    ]);

    // Ensure facade has a configured service (in case container was refreshed between tests)
    YouTube::swap(new YouTubeService('test-key'));
    $data = YouTube::fetch($video->identifier);

    // Apply the same rules as the Edit action (do not overwrite description)
    $video->title = $data->title;
    $video->duration = $data->durationSeconds;
    $video->published_at = $data->publishedAt ? \Illuminate\Support\Carbon::make($data->publishedAt) : null;
    $video->view_count = $data->viewCount;
    $video->like_count = $data->likeCount;
    $video->comment_count = $data->commentCount;
    $video->privacy = $data->privacyStatus;
    $video->embeddable = $data->embeddable ?? $video->embeddable;
    $video->broadcast = $data->broadcast;
    $video->save();

    $video->refresh();

    expect($video->title)->toBe('New Title')
        ->and($video->description)->toBe('Keep this description')
        ->and($video->duration)->toBe(130)
        ->and($video->view_count)->toBe(200)
        ->and($video->like_count)->toBe(20)
        ->and($video->comment_count)->toBe(2)
        ->and($video->privacy)->toBe('private')
        ->and($video->embeddable)->toBeTrue();
});
