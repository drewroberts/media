<?php

use DrewRoberts\Media\Models\Tag;

describe('Tag Model', function () {
    describe('Basic Creation', function () {
        test('creates tags with proper formatting', function () {
            $tag = createTag([
                'name' => 'TestTag',
                'type' => 'category',
            ]);

            expect($tag)
                ->toBeInstanceOf(Tag::class)
                ->name->toBe('#TestTag')
                ->slug->toBe('testtag')
                ->type->toBe('category');
        });

        test('formats names with hash prefix and studly case', function () {
            $tag = createTag([
                'name' => 'test tag name',
                'type' => 'test',
            ]);

            expect($tag->name)->toBe('#TestTagName');
        });

        test('generates slugs from names correctly', function () {
            $tag = createTag([
                'name' => 'Test Tag Name 123',
                'type' => 'test',
            ]);

            expect($tag->slug)->toBe('testtagname123');
        });
    });

    describe('Authentication Tracking', function () {
        test('sets creator_id when authenticated', function () {
            $user = authenticateUser();

            $tag = createTag([
                'name' => 'AuthorTag',
                'type' => 'author',
            ]);

            expect($tag)->toHaveCreator($user->id);
        });

        test('sets updater_id when saving changes', function () {
            $user = authenticateUser();

            $tag = createTag([
                'name' => 'UpdateTag',
                'type' => 'update',
            ]);

            $tag->update(['name' => 'UpdatedTag']);

            expect($tag)->toHaveUpdater($user->id);
        });

        test('maintains creator relationship', function () {
            $user = authenticateUser();

            $tag = createTag([
                'name' => 'RelationshipTag',
                'type' => 'test',
            ]);

            expect($tag->creator)
                ->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
                ->id->toBe($user->id);
        });

        test('maintains updater relationship', function () {
            $user = authenticateUser();

            $tag = createTag([
                'name' => 'UpdaterTag',
                'type' => 'test',
            ]);

            $tag->update(['name' => 'UpdatedTag']);

            expect($tag->updater)
                ->toBeInstanceOf(\Illuminate\Database\Eloquent\Model::class)
                ->id->toBe($user->id);
        });
    });

    describe('Routing and URLs', function () {
        test('uses slug as route key', function () {
            $tag = createTag([
                'name' => 'RouteTag',
                'type' => 'test',
            ]);

            expect($tag->getRouteKeyName())->toBe('slug');
        });

        test('generates correct path attribute', function () {
            $tag = createTag([
                'name' => 'PathTag',
                'type' => 'test',
            ]);

            expect($tag->path)->toBe('/tags/pathtag');
        });
    });

    describe('Type Filtering and Scoping', function () {
        beforeEach(function () {
            $this->tags = [
                createTag(['name' => 'CategoryTag1', 'type' => 'category']),
                createTag(['name' => 'CategoryTag2', 'type' => 'category']),
                createTag(['name' => 'AuthorTag', 'type' => 'author']),
            ];
        });

        test('scopes by type', function () {
            $categoryTags = Tag::withType('category')->get();
            $allTags = Tag::withType(null)->get();

            expect($categoryTags)->toHaveCount(2)
                ->and($allTags)->toHaveCount(3);
        });

        test('retrieves tags by specific type', function () {
            $categoryTags = Tag::getWithType('category');

            expect($categoryTags)->toHaveCount(2);
        });

        test('gets all unique types', function () {
            $types = Tag::getTypes();

            expect($types)
                ->toHaveCount(2)
                ->toContain('category')
                ->toContain('author');
        });
    });

    describe('Finding and Search', function () {
        test('finds tags from string', function () {
            $originalTag = createTag([
                'name' => 'FindableTag',
                'type' => 'test',
            ]);

            $foundTag = Tag::findFromString('#FindableTag', 'test');

            expect($foundTag)
                ->not->toBeNull()
                ->id->toBe($originalTag->id);
        });

        test('returns null for non-existent tags', function () {
            $foundTag = Tag::findFromString('NonExistentTag', 'test');

            expect($foundTag)->toBeNull();
        });
    });

    describe('Model Configuration', function () {
        test('implements sortable interface', function () {
            expect(Tag::class)->toImplement(\Spatie\EloquentSortable\Sortable::class);
        });

        test('uses factory trait', function () {
            expect(in_array(\Illuminate\Database\Eloquent\Factories\HasFactory::class, class_uses(Tag::class)))
                ->toBeTrue();
        });

        test('guards id field', function () {
            expect((new Tag)->getGuarded())->toBe(['id']);
        });
    });

    describe('Multiple Tags', function () {
        test('maintains uniqueness across multiple tags', function () {
            $tag1 = createTag(['name' => 'UniqueTag', 'type' => 'test']);
            $tag2 = createTag(['name' => 'AnotherUniqueTag', 'type' => 'test']);

            expect($tag1->slug)->toBe('uniquetag')
                ->and($tag2->slug)->toBe('anotheruniquetag')
                ->and($tag1->slug)->not->toBe($tag2->slug);
        });
    });
});
