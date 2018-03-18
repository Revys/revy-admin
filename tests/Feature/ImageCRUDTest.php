<?php

namespace Revys\RevyAdmin\Tests\Feature;

use App\Service;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Revys\Revy\Tests\Languages;
use Revys\Revy\Tests\Unit\ImagesTest;
use Revys\RevyAdmin\App\Indexer;
use Revys\RevyAdmin\Tests\TestCase;
use Revys\RevyAdmin\Tests\TestEntity;
use Revys\RevyAdmin\Tests\TestEntityController;

class ImageCRUDTest extends TestCase
{
    use DatabaseMigrations, Languages;

    private $object;

    public function setUp()
    {
        parent::setUp();

        $this->object = new TestEntity();

        app(Indexer::class)->mapClass('test_entity_controller', TestEntityController::class);

        Storage::fake('public');
        self::mockLocale();
        self::signIn();
    }

    /** @test */
    public function image_can_be_uploaded()
    {
        $object = create(TestEntity::class);

        $this->put(
            route('admin::update', ['test_entity', $object->id]),
            [
                'image' => UploadedFile::fake()->image('image.png')
            ]
        )->assertRedirect(route('admin::edit', ['test_entity', $object->id]));

        $this->assertCount(1, $object->fresh()->images());
    }

    /** @test */
    public function multiple_images_can_be_uploaded()
    {
        $object = create(TestEntity::class);

        $this->put(
            route('admin::update', ['test_entity', $object->id]),
            [
                'images' => [
                    UploadedFile::fake()->image('image.png'),
                    UploadedFile::fake()->image('image2.png'),
                    UploadedFile::fake()->image('image3.png')
                ]
            ]
        )->assertRedirect(route('admin::edit', ['test_entity', $object->id]));

        $this->assertCount(3, $object->fresh()->images());
    }

    /** @test */
    public function image_can_be_uploaded_when_adding_object()
    {
        $data = make(TestEntity::class)->toArray();
        $data['image'] = UploadedFile::fake()->image('image.png');

        unset($data['translations']);

        $request = $this->post(
            route('admin::insert', ['test_entity']),
            $data
        );

        $object = TestEntity::firstOrFail();

        $request->assertRedirect(route('admin::edit', ['test_entity', $object->id]));

        $this->assertCount(1, $object->fresh()->images());
    }


    /** @test */
    public function image_sets_when_item_can_only_have_one_image()
    {
        $object = create(TestEntity::class);

        $image = ImagesTest::createImage();
        $object->images()->add($image);

        $this->put(
            route('admin::update', ['test_entity', $object->id]),
            [
                'image' => [
                    UploadedFile::fake()->image('image.png'),
                    UploadedFile::fake()->image('image.png')
                ]
            ]
        )->assertRedirect(route('admin::edit', ['test_entity', $object->id]));

        $this->assertCount(1, $object->fresh()->images());
    }



    /** @test */
    public function image_can_be_deleted()
    {
        $object = create(TestEntity::class);

        $image = ImagesTest::createImage();
        $image = $object->images()->add($image);

        $this->assertCount(1, $object->fresh()->images());

        $this->post(route("admin::path", ['test_entity', 'remove_image']), [
            'id' => $image->id
        ]);

        $this->assertCount(0, $object->fresh()->images());
    }
}