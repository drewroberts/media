<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Tests\Feature\Nova;

use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoResourceTest extends TestCase
{
    //use DatabaseTransactions;
    use RefreshDatabase;

    /** @test */
    public function index()
    {
        Video::factory()->count(1)->create();

        $this->actingAs(self::createPermissionedUser('view videos', true));

        $response = $this->getJson('nova-api/videos')->assertOk();

        $this->assertCount(1, $response->json('resources'));
    }
}
