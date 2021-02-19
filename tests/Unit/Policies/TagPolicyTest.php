<?php

namespace DrewRoberts\Media\Tests\Unit\Policies;

use DrewRoberts\Media\Models\Tag;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagPolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_view_tags()
    {
        $user = self::createPermissionedUser('view tags', true);

        $this->assertTrue($user->can('viewAny', Tag::class));
    }

    /** @test */
    public function a_user_cannot_view_tags_without_permission()
    {
        $user = self::createPermissionedUser('view tags', false);

        $this->assertFalse($user->can('viewAny', Tag::class));
    }

    /** @test */
    public function a_user_can_view_an_specific_tag()
    {
        $user = self::createPermissionedUser('view tags', true);
        $tag = Tag::factory()->create();

        $this->assertTrue($user->can('view', $tag));
    }

    /** @test */
    public function a_user_cannot_view_an_specific_tag_without_permission()
    {
        $user = self::createPermissionedUser('view tags', false);
        $tag = Tag::factory()->create();

        $this->assertFalse($user->can('view', $tag));
    }

    /** @test */
    public function a_user_can_create_a_tag()
    {
        $user = self::createPermissionedUser('create tags', true);

        $this->assertTrue($user->can('create', Tag::class));
    }

    /** @test */
    public function a_user_cannot_create_a_tag_without_permission()
    {
        $user = self::createPermissionedUser('create tags', false);

        $this->assertFalse($user->can('create', Tag::class));
    }

    /** @test */
    public function a_user_can_update_a_tag()
    {
        $user = self::createPermissionedUser('update tags', true);
        $tag = Tag::factory()->create();

        $this->assertTrue($user->can('update', $tag));
    }

    /** @test */
    public function a_user_cannot_update_a_tag_without_permission()
    {
        $user = self::createPermissionedUser('update tags', false);
        $tag = Tag::factory()->create();

        $this->assertFalse($user->can('update', $tag));
    }

    /** @test */
    public function a_user_can_delete_a_tag()
    {
        $user = self::createPermissionedUser('delete tags', true);
        $tag = Tag::factory()->create();

        $this->assertTrue($user->can('delete', $tag));
    }

    /** @test */
    public function a_user_cannot_delete_a_tag_without_permission()
    {
        $user = self::createPermissionedUser('delete tags', false);
        $tag = Tag::factory()->create();

        $this->assertFalse($user->can('delete', $tag));
    }
}
