<?php

namespace DrewRoberts\Media\Tests\Unit;

use DrewRoberts\Media\Models\Tag;
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
}
