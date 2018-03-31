<?php

namespace Revys\RevyAdmin\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Revys\RevyAdmin\Tests\Languages;
use Revys\RevyAdmin\App\Indexer;
use Revys\RevyAdmin\App\User;
use Revys\RevyAdmin\Tests\TestCase;
use function GuzzleHttp\Psr7\uri_for;
use Revys\RevyAdmin\Tests\TestEntity;
use Revys\RevyAdmin\Tests\TestEntityController;

class ListTest extends  TestCase
{
    use DatabaseMigrations, Languages;

    private $object;

    public function setUp()
    {
        parent::setUp();

        self::signIn();
        self::mockLocale();

        $this->object = new TestEntity();

        app(Indexer::class)->mapClass('test_entity_controller', TestEntityController::class);
    }
    
    /** @test */
    public function list_can_be_accessed()
    {
        $this->get(route('admin::list', ['test_entity']))->assertSuccessful();
    }
    
    /** @test */
    public function list_display_items()
    {
        $object = create(TestEntity::class);
        $object2 = create(TestEntity::class);

        $this->get(route('admin::list', ['test_entity']))
            ->assertSee($object->string_field)
            ->assertSee($object2->string_field);
    }
}