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

    /** @test */
    public function it_attaches_a_tag()
    {
        $taggable = TaggableStub::create();

        $tag = Tag::factory()->create();

        $taggable->attachTag($tag);

        $this->assertCount(1, $taggable->tags);
        $this->assertEquals($tag->id, $taggable->tags->first()->id);
    }

    /** @test */
    public function it_detaches_a_tag()
    {
        $taggable = TaggableStub::create();
        $tag = Tag::factory()->create();

        $taggable->attachTag($tag);
        $taggable->detachTag($tag);

        $this->assertCount(0, $taggable->tags);
    }
}
