<?php

namespace DrewRoberts\Media\Tests\Unit\Models;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Tests\TestCase;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tipoff\Authorization\Models\User;

class VideoTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /** @test */
    public function it_has_an_identifier()
    {
        $identifier = $this->faker->slug;
        $video = Video::factory()->create(['identifier' => $identifier]);

        $this->assertEquals($identifier, $video->identifier);
    }

    /** @test */
    public function it_has_a_name()
    {
        $name = $this->faker->sentence;
        $video = Video::factory()->create(['name' => $name]);

        $this->assertEquals($name, $video->name);
    }

    /** @test */
    public function it_has_a_title()
    {
        $title = $this->faker->sentence;
        $video = Video::factory()->create(['title' => $title]);

        $this->assertEquals($title, $video->title);
    }

    /** @test */
    public function it_has_a_description()
    {
        $description = $this->faker->sentence;
        $video = Video::factory()->create(['description' => $description]);

        $this->assertEquals($description, $video->description);
    }

    /** @test */
    public function it_has_a_length()
    {
        $length = $this->faker->randomNumber;
        $video = Video::factory()->create(['length' => $length]);

        $this->assertEquals($length, $video->length);
    }

    /** @test */
    public function it_has_a_view_count()
    {
        $viewCount = $this->faker->randomNumber;
        $video = Video::factory()->create(['view_count' => $viewCount]);

        $this->assertEquals($viewCount, $video->view_count);
    }

    /** @test */
    public function it_has_a_like_count()
    {
        $likeCount = $this->faker->randomNumber;
        $video = Video::factory()->create(['like_count' => $likeCount]);

        $this->assertEquals($likeCount, $video->like_count);
    }

    /** @test */
    public function it_has_a_dislike_count()
    {
        $dislikeCount = $this->faker->randomNumber;
        $video = Video::factory()->create(['dislike_count' => $dislikeCount]);

        $this->assertEquals($dislikeCount, $video->dislike_count);
    }

    /** @test */
    public function it_has_a_comment_count()
    {
        $commentCount = $this->faker->randomNumber;
        $video = Video::factory()->create(['comment_count' => $commentCount]);

        $this->assertEquals($commentCount, $video->comment_count);
    }

    /** @test */
    public function it_has_a_broadcast_status()
    {
        $broadcast = 'live';
        $video = Video::factory()->create(['broadcast' => $broadcast]);

        $this->assertEquals($broadcast, $video->broadcast);
    }

    /** @test */
    public function it_has_a_privacy_type()
    {
        $privacy = 'unlisted';
        $video = Video::factory()->create(['privacy' => $privacy]);

        $this->assertEquals($privacy, $video->privacy);
    }

    /** @test */
    public function it_has_a_location()
    {
        $location = $this->faker->sentence;
        $video = Video::factory()->create(['location' => $location]);

        $this->assertEquals($location, $video->location);
    }

    /** @test */
    public function it_can_be_embedded()
    {
        $video = Video::factory()->create(['embeddable' => false]);

        $this->assertFalse($video->embeddable);
    }

    /** @test */
    public function it_has_a_stream_started_at_date()
    {
        $video = Video::factory()->create(['stream_started_at' => now()]);

        $this->assertInstanceOf(Carbon::class, $video->stream_started_at);
    }

    /** @test */
    public function it_has_a_stream_scheduled_at_date()
    {
        $video = Video::factory()->create(['stream_scheduled_at' => now()]);

        $this->assertInstanceOf(Carbon::class, $video->stream_scheduled_at);
    }

    /** @test */
    public function it_has_a_published_at_date()
    {
        $video = Video::factory()->create(['published_at' => now()]);

        $this->assertInstanceOf(Carbon::class, $video->published_at);
    }

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
    public function it_has_a_thumbnail()
    {
        $thumbnail = Image::factory()->create();
        $video = Video::factory()->create([
            'image_id' => $thumbnail->id,
        ]);

        $this->assertInstanceOf(Image::class, $video->image);
    }

    /** @test */
    public function it_keeps_track_of_who_created_it()
    {
        $user = User::factory()->create();

        $this->be($user);

        $video = Video::factory()->create();

        $this->assertInstanceOf(User::class, $video->creator);
        $this->assertEquals($user->id, $video->creator->id);
    }

    /** @test */
    public function it_keeps_track_of_who_updated_it()
    {
        $user = User::factory()->create();
        $video = Video::factory()->make();

        $this->be($user);

        $video->save();

        $this->assertInstanceOf(User::class, $video->updater);
        $this->assertEquals($user->id, $video->updater->id);
    }
}
