<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Tests\Feature\Nova;

use DrewRoberts\Media\Models\Tag;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagResourceTest extends TestCase
{
    //use DatabaseTransactions;
    use RefreshDatabase;

    /** @test */
    public function index()
    {
        Tag::factory()->count(1)->create();

        $this->actingAs(self::createPermissionedUser('view tags', true));

        $response = $this->getJson('nova-api/tags')->assertOk();

        $this->assertCount(1, $response->json('resources'));
    }
}
