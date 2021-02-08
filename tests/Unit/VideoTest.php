<?php

namespace DrewRoberts\Media\Tests\Unit;

use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use phpmock\phpunit\PHPMock;

class VideoTest extends TestCase
{
    use RefreshDatabase,
        PHPMock,
        WithFaker;

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
