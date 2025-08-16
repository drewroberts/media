<?php

use DrewRoberts\Media\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = \DrewRoberts\Media\Tests\TestUser::factory()->create();
});

it('can manage tag lifecycle with authentication', function () {
    // Login user
    Auth::login($this->user);

    // Create tag
    $tag = Tag::create([
        'name' => 'lifecycle tag',
        'type' => 'test',
    ]);

    expect($tag->creator_id)->toBe($this->user->id)
        ->and($tag->name)->toBe('#LifecycleTag')
        ->and($tag->slug)->toBe('lifecycletag');

    // Update tag
    $tag->update(['name' => 'updated lifecycle tag']);
    $tag->refresh();

    expect($tag->updater_id)->toBe($this->user->id)
        ->and($tag->name)->toBe('#UpdatedLifecycleTag');
});

it('can filter and organize tags by type', function () {
    // Create various tags
    collect(['category', 'author', 'topic', 'category', 'author'])->each(function ($type, $index) {
        Tag::create([
            'name' => "Tag {$index}",
            'type' => $type,
        ]);
    });

    expect(Tag::count())->toBe(5)
        ->and(Tag::getTypes()->count())->toBe(3)
        ->and(Tag::getWithType('category')->count())->toBe(2)
        ->and(Tag::getWithType('author')->count())->toBe(2)
        ->and(Tag::getWithType('topic')->count())->toBe(1);
});

it('handles tag finding and creation workflow', function () {
    // Try to find non-existent tag
    $tag = Tag::findFromString('NonExistent', 'test');
    expect($tag)->toBeNull();

    // Create the tag
    $tag = Tag::create(['name' => 'NonExistent', 'type' => 'test']);

    // Now find it
    $foundTag = Tag::findFromString('#NonExistent', 'test');
    expect($foundTag)->not->toBeNull()
        ->and($foundTag->id)->toBe($tag->id);
});
