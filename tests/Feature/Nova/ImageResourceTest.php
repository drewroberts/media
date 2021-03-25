<?php

declare(strict_types=1);

namespace DrewRoberts\Media\Tests\Feature\Nova;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Tipoff\Authorization\Models\User;

class ImageResourceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function index()
    {
        Config::set('filesystems.disks.cloudinary', [
            'driver' => 'cloudinary',
            'api_key' => env('CLOUDINARY_API_KEY', 'nonsense'),
            'api_secret' => env('CLOUDINARY_API_SECRET', 'nonsense'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'nonsense'),
        ]);
        
        Image::factory()->count(4)->create();

        $this->actingAs(User::factory()->create()->assignRole('Admin'));

        $response = $this->getJson('nova-api/images')
            ->assertOk();

        $this->assertCount(4, $response->json('resources'));
    }

    /** @test */
    public function show()
    {
        Config::set('filesystems.disks.cloudinary', [
            'driver' => 'cloudinary',
            'api_key' => env('CLOUDINARY_API_KEY', 'nonsense'),
            'api_secret' => env('CLOUDINARY_API_SECRET', 'nonsense'),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'nonsense'),
        ]);
        
        $user = User::factory()->create();
        $image = Image::factory()->create();

        $this->actingAs(User::factory()->create()->assignRole('Admin'));

        $response = $this->getJson("nova-api/images/{$image->id}")
            ->assertOk();

        $this->assertEquals($image->id, $response->json('resource.id.value'));
    }
}
