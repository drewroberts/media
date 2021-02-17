<?php

namespace DrewRoberts\Media\Tests\Unit\Models;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Models\Video;
use DrewRoberts\Media\Tests\Support\Models\User;
use DrewRoberts\Media\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use phpmock\phpunit\PHPMock;

class ImageTest extends TestCase
{
    use RefreshDatabase,
        PHPMock,
        WithFaker;

    /** @test */
    public function it_has_a_file_name()
    {
        $filename = $this->faker->word . '.' . $this->faker->fileExtension;
        $image = Image::factory()->create(['filename' => $filename]);

        $this->assertEquals($filename, $image->filename);
    }

    /** @test */
    public function it_has_a_width()
    {
        $width = $this->faker->randomNumber;
        $image = Image::factory()->create(['width' => $width]);

        $this->assertEquals($width, $image->width);
    }

    /** @test */
    public function it_has_a_height()
    {
        $height = $this->faker->randomNumber;
        $image = Image::factory()->create(['height' => $height]);

        $this->assertEquals($height, $image->height);
    }

    /** @test */
    public function it_has_a_description()
    {
        $description = $this->faker->paragraph;
        $image = Image::factory()->create(['description' => $description]);

        $this->assertEquals($description, $image->description);
    }

    /** @test */
    public function it_has_an_alternative_text()
    {
        $alt = $this->faker->paragraph;
        $image = Image::factory()->create(['alt' => $alt]);

        $this->assertEquals($alt, $image->alt);
    }

    /** @test */
    public function it_gives_credits_for_the_image()
    {
        $credit = $this->faker->sentence;
        $image = Image::factory()->create(['credit' => $credit]);

        $this->assertEquals($credit, $image->credit);
    }

    /** @test */
    public function it_has_videos()
    {
        $image = Image::factory()->create();

        $video = Video::factory()->create();
        $image->videos()->save($video);

        $this->assertInstanceOf(Collection::class, $image->videos);
        $this->assertEquals($video->id, $image->videos->first()->id);
    }

    /** @test */
    public function it_has_an_url()
    {
        config(['media.cloudinary_cloud_name' => 'test']);

        $image = Image::factory()->create();

        $this->assertEquals(
            "https://res.cloudinary.com/test/{$image->filename}",
            $image->url
        );
    }

    /** @test */
    public function it_determines_the_dimensions_if_empty()
    {
        $image = Image::factory()->make([
            'filename' => 'test.jpg',
            'width' => null,
            'height' => null,
        ]);

        $this->getFunctionMock('DrewRoberts\Media\Models', 'getimagesize')
            ->expects($this->once())
            ->with($image->url)
            ->willReturn([24, 60]);

        $image->save();

        $this->assertEquals(24, $image->width);
        $this->assertEquals(60, $image->height);
    }

    /** @test */
    public function it_keeps_track_of_who_created_it()
    {
        $user = User::factory()->create();

        $this->be($user);

        $image = Image::factory()->create();

        $this->assertInstanceOf(User::class, $image->creator);
        $this->assertEquals($user->id, $image->creator->id);
    }

    /** @test */
    public function it_keeps_track_of_who_updated_it()
    {
        $user = User::factory()->create();
        $image = Image::factory()->make();

        $this->be($user);

        $image->save();

        $this->assertInstanceOf(User::class, $image->updater);
        $this->assertEquals($user->id, $image->updater->id);
    }
}
