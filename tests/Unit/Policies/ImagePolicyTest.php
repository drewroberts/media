<?php

namespace DrewRoberts\Media\Tests\Unit\Policies;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImagePolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_images()
    {
        $user = self::createPermissionedUser('view images', true);

        $this->assertTrue($user->can('viewAny', Image::class));
    }

    /** @test */
    public function a_user_cannot_view_images_without_permission()
    {
        $user = self::createPermissionedUser('view images', false);

        $this->assertFalse($user->can('viewAny', Image::class));
    }

    /** @test */
    public function a_user_can_view_an_specific_image()
    {
        $user = self::createPermissionedUser('view images', true);
        $image = Image::factory()->create();

        $this->assertTrue($user->can('view', $image));
    }

    /** @test */
    public function a_user_cannot_view_an_specific_image_without_permission()
    {
        $user = self::createPermissionedUser('view images', false);
        $image = Image::factory()->create();

        $this->assertFalse($user->can('view', $image));
    }

    /** @test */
    public function a_user_can_create_an_image()
    {
        $user = self::createPermissionedUser('create images', true);

        $this->assertTrue($user->can('create', Image::class));
    }

    /** @test */
    public function a_user_cannot_create_an_image_without_permission()
    {
        $user = self::createPermissionedUser('create images', false);

        $this->assertFalse($user->can('create', Image::class));
    }

    /** @test */
    public function a_user_can_update_an_image()
    {
        $user = self::createPermissionedUser('update images', true);
        $image = Image::factory()->create();

        $this->assertTrue($user->can('update', $image));
    }

    /** @test */
    public function a_user_cannot_update_an_image_without_permission()
    {
        $user = self::createPermissionedUser('update images', false);
        $image = Image::factory()->create();

        $this->assertFalse($user->can('update', $image));
    }
}
