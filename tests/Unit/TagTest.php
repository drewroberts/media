<?php

namespace DrewRoberts\Media\Tests\Unit;

use DrewRoberts\Media\Models\Tag;
use DrewRoberts\Media\Tests\Support\Models\User;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class TagTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /** @test */
    public function it_has_a_path()
    {
        $tag = Tag::factory()->create();

        $this->assertEquals($tag->path, "/tags/{$tag->slug}");
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
