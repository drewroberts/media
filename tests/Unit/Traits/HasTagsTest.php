<?php

namespace DrewRoberts\Media\Tests\Unit\Traits;

use DrewRoberts\Media\Models\Tag;
use DrewRoberts\Media\Tests\Support\TaggableStub;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class HasTagsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Schema::create('taggable_stubs', static function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /** @test */
    public function it_has_tags()
    {
        $tag = Tag::factory()->create();
        $taggable = TaggableStub::create();
        $taggable->tags()->save($tag);

        $this->assertInstanceOf(Collection::class, $taggable->tags);
        $this->assertInstanceOf(Tag::class, $taggable->tags->first());
    }
}
