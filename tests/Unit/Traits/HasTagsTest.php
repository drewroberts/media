<?php

namespace DrewRoberts\Media\Tests\Unit\Traits;

use DrewRoberts\Media\Models\Tag;
use DrewRoberts\Media\Tests\Support\TaggableStub;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HasTagsTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

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
    public function it_attaches_a_tag_by_name()
    {
        $name = '#TagName';
        $taggable = TaggableStub::create();

        $tag = Tag::factory()->create([
            'name' => $name,
            'type' => null,
        ]);

        $taggable->attachTagByName($name);

        $this->assertCount(1, $taggable->tags);
        $this->assertEquals($tag->name, $taggable->tags->first()->name);
    }

    /** @test */
    public function it_attaches_a_tag_by_name_and_type()
    {
        $name = '#TagName';
        $type = $this->faker->word;
        $taggable = TaggableStub::create();

        $tag = Tag::factory()->create([
            'name' => $name,
            'type' => $type,
        ]);

        $taggable->attachTagByName($name, $type);

        $this->assertCount(1, $taggable->tags);
        $this->assertEquals($tag->name, $taggable->tags->first()->name);
    }

    /** @test */
    public function it_attaches_a_tag_by_name_even_if_it_does_not_exist_yet()
    {
        $name = '#TagName';
        $taggable = TaggableStub::create();

        $taggable->attachTagByName($name);

        $this->assertCount(1, $taggable->tags);
        $this->assertEquals($name, $taggable->tags->first()->name);
    }

    /** @test */
    public function it_attaches_a_tag_by_name_and_type_even_if_it_does_not_exist_yet()
    {
        $name = '#TagName';
        $type = $this->faker->word;
        $taggable = TaggableStub::create();

        $taggable->attachTagByName($name, $type);

        $this->assertCount(1, $taggable->tags);
        $this->assertEquals($name, $taggable->tags->first()->name);
        $this->assertEquals($type, $taggable->tags->first()->type);
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

    /** @test */
    public function it_detaches_a_tag_by_name()
    {
        $name = '#TagName';
        $taggable = TaggableStub::create();

        $tag = Tag::factory()->create([
            'name' => $name,
            'type' => null,
        ]);

        $taggable->attachTag($tag);
        $taggable->detachTagByName($name);

        $this->assertCount(0, $taggable->tags);
    }

    /** @test */
    public function it_detaches_a_tag_by_name_and_type()
    {
        $name = '#TagName';
        $type = $this->faker->word;
        $taggable = TaggableStub::create();

        $tag = Tag::factory()->create([
            'name' => $name,
            'type' => $type,
        ]);

        $taggable->attachTag($tag);
        $taggable->detachTagByName($name, $type);

        $this->assertCount(0, $taggable->tags);
    }

    /** @test */
    public function it_syncs_tags()
    {
        $taggable = TaggableStub::create();
        $previousTag = Tag::factory()->create();

        $taggable->attachTag($previousTag);

        $taggable->syncTags(
            Tag::factory()->count(3)->create()
        );

        $this->assertCount(3, $taggable->tags);
        $this->assertFalse($taggable->tags->contains($previousTag));
    }

    /** @test */
    public function it_deletes_all_tag_associations_as_taggable_is_being_deleted()
    {
        $taggable = TaggableStub::create();
        $tags = Tag::factory()->count(3)->create();

        $tags->each(static function ($tag) use ($taggable) {
            $taggable->attachTag($tag);
        });

        $taggable->delete();

        $count = DB::table('taggables')
            ->where('taggable_id', $taggable->id)
            ->count();

        $this->assertEquals(0, $count);
    }
}
