<?php

namespace DrewRoberts\Media\Tests\Unit;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class VideoTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /** @test */
    public function it_throws_an_exception_when_there_is_no_identifier()
    {
        $this->expectException(Exception::class);

        $video = Video::factory()->make(['identifier' => null]);

        $video->save();
    }

    /** @test */
    public function it_has_a_source()
    {
        $source = $this->faker->word;
        $video = Video::factory()->create(['source' => $source]);

        $this->assertEquals($source, $video->source);
    }

    /** @test */
    public function the_video_source_defaults_to_youtube()
    {
        $video = Video::factory()->make(['source' => null]);

        $video->save();

        $this->assertEquals('youtube', $video->source);
    }

    /** @test */
    public function it_is_associated_with_an_image()
    {
        $image = Image::factory()->create();
        $video = Video::factory()->create([
            'image_id' => $image->id,
        ]);

        $this->assertInstanceOf(Image::class, $video->image);
    }

    /** @test */
    public function it_keeps_track_of_who_created_it()
    {
        $user = (config('media.models.user'))::factory()->create();

        $this->be($user);

        $video = Video::factory()->create();

        $this->assertInstanceOf(config('media.models.user'), $video->creator);
        $this->assertEquals($user->id, $video->creator->id);
    }

    /** @test */
    public function it_keeps_track_of_who_updated_it()
    {
        $user = (config('media.models.user'))::factory()->create();
        $video = Video::factory()->make();

        $this->be($user);

        $video->save();

        $this->assertInstanceOf(config('media.models.user'), $video->updater);
        $this->assertEquals($user->id, $video->updater->id);
    }
}
