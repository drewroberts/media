<?php

namespace DrewRoberts\Media\Tests\Unit\Policies;

use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_videos()
    {
        $user = self::createPermissionedUser('view videos', true);

        $this->assertTrue($user->can('viewAny', Video::class));
    }

    /** @test */
    public function a_user_cannot_view_videos_without_permission()
    {
        $user = self::createPermissionedUser('view videos', false);

        $this->assertFalse($user->can('viewAny', Video::class));
    }

    /** @test */
    public function a_user_can_view_an_specific_video()
    {
        $user = self::createPermissionedUser('view videos', true);
        $video = Video::factory()->create();

        $this->assertTrue($user->can('view', $video));
    }

    /** @test */
    public function a_user_cannot_view_an_specific_video_without_permission()
    {
        $user = self::createPermissionedUser('view videos', false);
        $video = Video::factory()->create();

        $this->assertFalse($user->can('view', $video));
    }

    /** @test */
    public function a_user_can_create_a_video()
    {
        $user = self::createPermissionedUser('create videos', true);

        $this->assertTrue($user->can('create', Video::class));
    }

    /** @test */
    public function a_user_cannot_create_a_video_without_permission()
    {
        $user = self::createPermissionedUser('create videos', false);

        $this->assertFalse($user->can('create', Video::class));
    }

    /** @test */
    public function a_user_can_update_a_video()
    {
        $user = self::createPermissionedUser('update videos', true);
        $video = Video::factory()->create();

        $this->assertTrue($user->can('update', $video));
    }

    /** @test */
    public function a_user_cannot_update_a_video_without_permission()
    {
        $user = self::createPermissionedUser('update videos', false);
        $video = Video::factory()->create();

        $this->assertFalse($user->can('update', $video));
    }

    /** @test */
    public function a_user_cannot_force_delete_a_video()
    {
        $user = self::createPermissionedUser('force delete videos', true);
        $video = Video::factory()->create();

        $this->assertFalse($user->can('forceDelete', $video));
    }

    /** @test */
    public function a_user_cannot_restore_a_video()
    {
        $user = self::createPermissionedUser('restore videos', true);
        $video = Video::factory()->create();

        $this->assertFalse($user->can('restore', $video));
    }
}
