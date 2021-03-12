<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Tests\Feature\Nova;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class ImageResourceTest extends TestCase
{
    //use DatabaseTransactions;
    use RefreshDatabase;

    /** @test */
    public function index()
    {
        Config::set('app.key', 'base64:CA0WFs+ECA4gq/G95GpRwEaYsoNdUF0cAziYkc83ISE=');

        Config::set('filesystems.disks.cloudinary', [
            'driver' => 'cloudinary',
            'api_key' => env('CLOUDINARY_API_KEY', 'nonsense'),
            'api_secret' => env('CLOUDINARY_API_SECRET', 'nonsense'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'nonsense'),
        ]);
        
        Image::factory()->count(1)->create();

        $this->actingAs(self::createPermissionedUser('view images', true));

        $response = $this->getJson('nova-api/images')->assertOk();

        $this->assertCount(1, $response->json('resources'));
    }
}
