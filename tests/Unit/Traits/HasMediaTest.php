<?php

namespace DrewRoberts\Media\Tests\Unit\Traits;

use DrewRoberts\Media\Models\Image;
use DrewRoberts\Media\Tests\TestCase;
use DrewRoberts\Media\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class HasMediaTest extends TestCase
{
    use RefreshDatabase;

    protected $model;

    public function setUp(): void
    {
        parent::setUp();
        $model = new class extends Model {
            use HasMedia;
            protected $table = 'table_with_media';
        };

        $this->model = Mockery::mock($model);
        config(['filesystem.disks.cloudinary.cloud_name' => 'test_cloud']);
    }

    /** @test */
    public function get_image()
    {
        $this->assertInstanceOf(BelongsTo::class, $this->model->image());
        $this->assertEquals('image_id', $this->model->image()->getForeignKeyName());
        $this->assertEquals('table_with_media.id', $this->model->image()->getQualifiedParentKeyName());
    }

    /** @test */
    public function get_ogimage()
    {
        $this->assertInstanceOf(BelongsTo::class, $this->model->ogimage());
        $this->assertEquals('ogimage_id', $this->model->ogimage()->getForeignKeyName());
        $this->assertEquals('table_with_media.id', $this->model->ogimage()->getQualifiedParentKeyName());
    }

    /** @test */
    public function get_video()
    {
        $this->assertInstanceOf(BelongsTo::class, $this->model->video());
        $this->assertEquals('video_id', $this->model->video()->getForeignKeyName());
        $this->assertEquals('table_with_media.id', $this->model->video()->getQualifiedParentKeyName());
    }

    /** @test */
    public function get_image_path()
    {
        $image = Image::factory()->create();
        $cloudName = config('filesystem.disks.cloudinary.cloud_name');
        $this->model->forceFill(['image_id' => $image->id]);

        $this->assertEquals("https://res.cloudinary.com/{$cloudName}/t_cover/{$image->filename}", $this->model->imagePath);
    }

    /** @test */
    public function get_placeholder_path()
    {
        $image = Image::factory()->create();
        $cloudName = config('filesystem.disks.cloudinary.cloud_name');
        $this->model->forceFill(['image_id' => $image->id]);

        $this->assertEquals("https://res.cloudinary.com/{$cloudName}/t_coverplaceholder/{$image->filename}", $this->model->placeholderPath);
    }

    /** @test */
    public function get_image_path_if_image_not_related()
    {
        $this->assertEquals(url('img/ogimage.jpg'), $this->model->imagePath);
    }

    /** @test */
    public function get_placeholder_path_if_image_not_related()
    {
        $this->assertEquals(url('img/ogimage.jpg'), $this->model->placeholderPath);
    }
}
