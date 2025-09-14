<?php

use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Global Test Helpers
|--------------------------------------------------------------------------
|
| Here you may define helpers that are available in all tests.
|
*/

function createUser(): \DrewRoberts\Media\Tests\TestUser
{
    return \DrewRoberts\Media\Tests\TestUser::factory()->create();
}

function createImage(array $attributes = []): \DrewRoberts\Media\Models\Image
{
    return \DrewRoberts\Media\Models\Image::create(array_merge([
        'filename' => '/test-image.jpg',
        'width' => 800,
        'height' => 600,
    ], $attributes));
}

function createTag(array $attributes = []): \DrewRoberts\Media\Models\Tag
{
    return \DrewRoberts\Media\Models\Tag::create(array_merge([
        'name' => 'TestTag',
        'type' => 'test',
    ], $attributes));
}

function createVideo(array $attributes = []): \DrewRoberts\Media\Models\Video
{
    return \DrewRoberts\Media\Models\Video::create(array_merge([
        'identifier' => 'VHpxrjqIJDc',
        'source' => 'youtube',
        'title' => 'Test Video',
    ], $attributes));
}

function authenticateUser(): \DrewRoberts\Media\Tests\TestUser
{
    $user = createUser();
    \Illuminate\Support\Facades\Auth::login($user);

    return $user;
}

function sampleImageData(): array
{
    return [
        'filename' => '/sample-image.jpg',
        'width' => 1920,
        'height' => 1080,
        'description' => 'Sample image',
        'alt' => 'Sample alt text',
        'credit' => 'Sample Photographer',
    ];
}

function sampleVideoData(): array
{
    return [
        'identifier' => 'sampleVideoId',
        'source' => 'youtube',
        'title' => 'Sample Video',
        'description' => 'Sample description',
        'duration' => 120,
    ];
}

function sampleTagData(): array
{
    return [
        'name' => 'SampleTag',
        'type' => 'category',
    ];
}

function youtubeApiResponse(array $overrides = []): array
{
    return array_merge([
        'items' => [[
            'id' => 'VHpxrjqIJDc',
            'snippet' => [
                'title' => 'API Title',
                'description' => 'API Description',
                'channelTitle' => 'API Channel',
                'publishedAt' => '2024-01-01T00:00:00Z',
                'thumbnails' => [
                    'maxres' => ['url' => 'https://i.ytimg.com/maxresdefault.jpg'],
                ],
            ],
            'contentDetails' => [
                'duration' => 'PT2M0S',
            ],
            'statistics' => [
                'viewCount' => '100',
                'likeCount' => '10',
                'commentCount' => '1',
            ],
            'status' => [
                'privacyStatus' => 'public',
                'embeddable' => true,
            ],
            'liveStreamingDetails' => [],
        ]],
    ], $overrides);
}

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often want to check if values are equal
| or if some functionality works as expected. These custom expectations
| provide additional assertions for the Media package.
|
*/

expect()->extend('toHaveCreator', function (int $userId) {
    return $this->creator_id->toBe($userId);
});

expect()->extend('toHaveUpdater', function (int $userId) {
    return $this->updater_id->toBe($userId);
});

expect()->extend('toBeFormattedTag', function (string $expectedName, ?string $expectedSlug = null) {
    $expectedSlug = $expectedSlug ?: strtolower(str_replace(['#', ' '], ['', ''], $expectedName));

    return $this->name->toBe($expectedName)
        ->and($this->slug)->toBe($expectedSlug);
});

expect()->extend('toHaveValidImageDimensions', function () {
    return $this->width->toBeInt()->toBeGreaterThan(0)
        ->and($this->height)->toBeInt()->toBeGreaterThan(0);
});

expect()->extend('toHaveValidVideoIdentifier', function () {
    return $this->identifier->toMatch('/^[a-zA-Z0-9_-]+$/')
        ->and($this->source)->toBeIn(['youtube', 'vimeo']);
});

expect()->extend('toHaveAuthenticatedTracking', function (int $userId) {
    return $this->creator_id->toBe($userId)
        ->and($this->updater_id)->toBe($userId);
});

expect()->extend('toBeYouTubeUrl', function (string $expectedId) {
    return $this->toBe("https://youtu.be/{$expectedId}");
});
