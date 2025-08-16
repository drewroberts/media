<?php

use DrewRoberts\Media\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a test user for the relationships
    $this->user = \DrewRoberts\Media\Tests\TestUser::factory()->create();
});

it('can create a tag', function () {
    $tag = Tag::create([
        'name' => 'TestTag',
        'type' => 'category',
    ]);

    expect($tag)->toBeInstanceOf(Tag::class)
        ->and($tag->name)->toBe('#TestTag')
        ->and($tag->slug)->toBe('testtag')
        ->and($tag->type)->toBe('category');
});

it('automatically sets creator_id when authenticated', function () {
    Auth::login($this->user);

    $tag = Tag::create([
        'name' => 'AuthorTag',
        'type' => 'author',
    ]);

    expect($tag->creator_id)->toBe($this->user->id);
});

it('automatically sets updater_id when saving while authenticated', function () {
    Auth::login($this->user);

    $tag = Tag::create([
        'name' => 'UpdateTag',
        'type' => 'update',
    ]);

    $tag->name = 'UpdatedTag';
    $tag->save();

    expect($tag->updater_id)->toBe($this->user->id);
});

it('formats name with hash prefix and studly case', function () {
    $tag = Tag::create([
        'name' => 'test tag name',
        'type' => 'test',
    ]);

    expect($tag->name)->toBe('#TestTagName');
});

it('generates correct slug from name', function () {
    $tag = Tag::create([
        'name' => 'Test Tag Name 123',
        'type' => 'test',
    ]);

    expect($tag->slug)->toBe('testtagname123');
});

it('has creator relationship', function () {
    Auth::login($this->user);

    $tag = Tag::create([
        'name' => 'RelationshipTag',
        'type' => 'test',
    ]);

    expect($tag->creator)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
        ->and($tag->creator->id)->toBe($this->user->id);
});

it('has updater relationship', function () {
    Auth::login($this->user);

    $tag = Tag::create([
        'name' => 'UpdaterTag',
        'type' => 'test',
    ]);

    $tag->update(['name' => 'UpdatedTag']);

    expect($tag->updater)->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
        ->and($tag->updater->id)->toBe($this->user->id);
});

it('uses slug as route key', function () {
    $tag = Tag::create([
        'name' => 'RouteTag',
        'type' => 'test',
    ]);

    expect($tag->getRouteKeyName())->toBe('slug');
});

it('generates correct path attribute', function () {
    $tag = Tag::create([
        'name' => 'PathTag',
        'type' => 'test',
    ]);

    expect($tag->path)->toBe('/tags/pathtag');
});

it('can scope by type', function () {
    Tag::create(['name' => 'CategoryTag', 'type' => 'category']);
    Tag::create(['name' => 'AuthorTag', 'type' => 'author']);
    Tag::create(['name' => 'AnotherCategoryTag', 'type' => 'category']);

    $categoryTags = Tag::withType('category')->get();
    $allTags = Tag::withType(null)->get();

    expect($categoryTags)->toHaveCount(2)
        ->and($allTags)->toHaveCount(3);
});

it('can get tags by type', function () {
    Tag::create(['name' => 'CategoryTag1', 'type' => 'category']);
    Tag::create(['name' => 'CategoryTag2', 'type' => 'category']);
    Tag::create(['name' => 'AuthorTag', 'type' => 'author']);

    $categoryTags = Tag::getWithType('category');

    expect($categoryTags)->toHaveCount(2);
});

it('can find tag from string', function () {
    $originalTag = Tag::create([
        'name' => 'FindableTag',
        'type' => 'test',
    ]);

    $foundTag = Tag::findFromString('#FindableTag', 'test');

    expect($foundTag)->not->toBeNull()
        ->and($foundTag->id)->toBe($originalTag->id);
});

it('returns null when tag not found', function () {
    $foundTag = Tag::findFromString('NonExistentTag', 'test');

    expect($foundTag)->toBeNull();
});

it('can get all types', function () {
    Tag::create(['name' => 'CategoryTag', 'type' => 'category']);
    Tag::create(['name' => 'AuthorTag', 'type' => 'author']);
    Tag::create(['name' => 'AnotherCategoryTag', 'type' => 'category']);

    $types = Tag::getTypes();

    expect($types)->toHaveCount(2)
        ->and($types->contains('category'))->toBeTrue()
        ->and($types->contains('author'))->toBeTrue();
});

it('is sortable', function () {
    expect(Tag::class)->toImplement(\Spatie\EloquentSortable\Sortable::class);
});

it('uses factory', function () {
    expect(in_array(\Illuminate\Database\Eloquent\Factories\HasFactory::class, class_uses(Tag::class)))
        ->toBeTrue();
});

it('has guarded id field', function () {
    $tag = new Tag;

    expect($tag->getGuarded())->toBe(['id']);
});

it('can create multiple tags and maintain uniqueness', function () {
    $tag1 = Tag::create(['name' => 'UniqueTag', 'type' => 'test']);
    $tag2 = Tag::create(['name' => 'AnotherUniqueTag', 'type' => 'test']);

    expect($tag1->slug)->toBe('uniquetag')
        ->and($tag2->slug)->toBe('anotheruniquetag')
        ->and($tag1->slug)->not->toBe($tag2->slug);
});
