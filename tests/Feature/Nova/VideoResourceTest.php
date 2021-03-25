<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Tests\Feature\Nova;

use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tipoff\Authorization\Models\User;

class VideoResourceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function index()
    {
        Video::factory()->count(4)->create();

        $this->actingAs(User::factory()->create()->assignRole('Admin'));

        $response = $this->getJson('nova-api/videos')
            ->assertOk();

        $this->assertCount(4, $response->json('resources'));
    }

    /** @test */
    public function show()
    {
        $user = User::factory()->create();
        $video = Video::factory()->create();

        $this->actingAs(User::factory()->create()->assignRole('Admin'));

        $response = $this->getJson("nova-api/videos/{$video->id}")
            ->assertOk();

        $this->assertEquals($video->id, $response->json('resource.id.value'));
    }
}
