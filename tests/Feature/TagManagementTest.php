<?php

use DrewRoberts\Media\Models\Tag;

beforeEach(function () {
    $this->user = createUser();
});

describe('Tag Management', function () {
    describe('Lifecycle Operations', function () {
        test('creates tags with proper creator tracking', function () {
            $user = authenticateUser();
            $tag = createTag(['name' => 'lifecycle tag']);

            expect($tag)
                ->toHaveCreator($user->id)
                ->name->toBe('#LifecycleTag')
                ->slug->toBe('lifecycletag');
        });

        test('updates tags with proper updater tracking', function () {
            $user = authenticateUser();
            $tag = createTag(['name' => 'lifecycle tag']);

            $tag->update(['name' => 'updated lifecycle tag']);

            expect($tag->fresh())
                ->toHaveUpdater($user->id)
                ->name->toBe('#UpdatedLifecycleTag');
        });
    });

    describe('Type Organization', function () {
        beforeEach(function () {
            collect(['category', 'author', 'topic', 'category', 'author'])
                ->each(fn ($type, $i) => createTag(['name' => "Tag {$i}", 'type' => $type]));
        });

        test('counts total tags correctly', function () {
            expect(Tag::count())->toBe(5);
        });

        test('identifies unique tag types', function () {
            expect(Tag::getTypes())->toHaveCount(3);
        });

        test('filters tags by specific type', function () {
            expect(Tag::getWithType('category'))->toHaveCount(2)
                ->and(Tag::getWithType('author'))->toHaveCount(2)
                ->and(Tag::getWithType('topic'))->toHaveCount(1);
        });
    });

    describe('Find and Create Workflow', function () {
        test('handles non-existent tag search', function () {
            expect(Tag::findFromString('NonExistent', 'test'))->toBeNull();
        });

        test('finds existing tags by string', function () {
            $tag = createTag(['name' => 'Existing']);

            $found = Tag::findFromString('#Existing', 'test');

            expect($found)
                ->not->toBeNull()
                ->id->toBe($tag->id);
        });
    });
});
