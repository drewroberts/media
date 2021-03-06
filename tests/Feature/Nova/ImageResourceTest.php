<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Tests\Feature\Nova;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImageResourceTest extends TestCase
{
    //use DatabaseTransactions;
    use RefreshDatabase; 

    /** @test */
    public function index()
    {
        Image::factory()->count(1)->create();

        $this->actingAs(self::createPermissionedUser('view images', true));

        $response = $this->getJson('nova-api/images')->assertOk();

        $this->assertCount(1, $response->json('resources'));
    }
}
