<?php

namespace DrewRoberts\Media\Tests\Unit\Models;

use DrewRoberts\Media\Models\Tag;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tipoff\Authorization\Models\User;

class TagTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /** @test */
    public function it_has_a_name()
    {
        $name = '#TagName';
        $tag = Tag::factory()->create(['name' => $name]);

        $this->assertEquals($name, $tag->name);
    }

    /** @test */
    public function it_transforms_the_name_before_saving()
    {
        $name = "#$%^&*()Tag name 01~`!@-_+={}[]|\/:;\"'<>,.?";
        $tag = Tag::factory()->make(['name' => $name]);

        $tag->save();

        $this->assertEquals('#TagName01', $tag->name);
        $this->assertEquals('tagname01', $tag->slug);
    }

    /** @test */
    public function it_strips_all_hashtags_and_add_one_at_the_beginning()
    {
        $name = "#Tag #Name##";
        $tag = Tag::factory()->make(['name' => $name]);

        $tag->save();

        $this->assertEquals('#TagName', $tag->name);
        $this->assertEquals('tagname', $tag->slug);
    }
    
    /** @test */
    public function it_has_a_path()
    {
        $tag = Tag::factory()->create();

        $this->assertEquals($tag->path, "/tags/{$tag->slug}");
    }

    /** @test */
    public function tag_scope_with_type()
    {
        Tag::factory()->create(['type' => 'type_1']);
        Tag::factory()->create(['type' => 'type_1']);
        Tag::factory()->create(['type' => 'type_2']);

        $count = Tag::withType('type_1')->count();
        $this->assertEquals(2, $count);

        $count = Tag::withType()->count();
        $this->assertEquals(3, $count);
    }

    /** @test */
    public function it_generates_a_slug_on_save()
    {
        $tag = Tag::factory()->make([
            'name' => '#TagName',
        ]);

        $tag->save();

        $this->assertNotNull($tag->slug);
        $this->assertEquals('tagname', $tag->slug);
    }

    /** @test */
    public function it_uses_its_slug_for_route_model_binding()
    {
        $tag = Tag::factory()->create();

        $this->assertEquals('slug', $tag->getRouteKeyName());
    }

    /** @test */
    public function it_returns_all_tags_of_a_certain_type_in_order()
    {
        Tag::factory()->create(['type' => 'type_1']);
        Tag::factory()->create(['type' => 'type_1']);
        Tag::factory()->create(['type' => 'type_2']);

        $tags = Tag::getWithType('type_1');

        $this->assertCount(2, $tags);
    }

    /** @test */
    public function it_finds_a_tag_by_name()
    {
        $name = '#TagName';
        $target = Tag::factory()->create(['name' => $name]);

        $tag = Tag::findFromString($name);

        $this->assertNotNull($tag);
        $this->assertEquals($tag->id, $target->id);

        $this->assertNull(
            Tag::findFromString('invalid_name')
        );
    }

    /** @test */
    public function it_finds_a_tag_from_a_string_that_matches_a_certain_type()
    {
        $name = '#TagName';
        $type = $this->faker->word;

        Tag::factory()->create([
            'name' => $name,
            'type' => $type,
        ]);

        $this->assertNotNull(
            Tag::findFromString($name, $type)
        );

        $this->assertNull(
            Tag::findFromString($name)
        );
    }

    /** @test */
    public function it_lists_all_tag_types_without_repeating_them()
    {
        Tag::factory()->create(['type' => 'type_1']);
        Tag::factory()->create(['type' => 'type_1']);
        Tag::factory()->create(['type' => 'type_2']);

        $types = Tag::getTypes();

        $this->assertCount(2, $types);
        $this->assertContains('type_1', $types);
        $this->assertContains('type_2', $types);
    }

    /** @test */
    public function it_keeps_track_of_who_created_it()
    {
        $user = User::factory()->create();

        $this->be($user);

        $tag = Tag::factory()->create();

        $this->assertInstanceOf(User::class, $tag->creator);
        $this->assertEquals($user->id, $tag->creator->id);
    }

    /** @test */
    public function it_keeps_track_of_who_updated_it()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->make();

        $this->be($user);

        $tag->save();

        $this->assertInstanceOf(User::class, $tag->updater);
        $this->assertEquals($user->id, $tag->updater->id);
    }
}
