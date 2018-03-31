<?php

namespace Revys\RevyAdmin\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Revys\RevyAdmin\App\AdminMenu;
use Revys\RevyAdmin\App\Http\Composers\GlobalsComposer;
use Revys\RevyAdmin\App\Http\Controllers\LanguageControllerBase;
use Revys\RevyAdmin\Tests\Languages;
use Revys\RevyAdmin\Tests\TestCase;

class AdminMenuTest extends  TestCase
{
    use DatabaseMigrations, Languages;

    public function setUp()
    {
        parent::setUp();

        self::signIn();
        self::mockLocale();

        $this->seed(\Revys\Revy\Database\Seeds\DatabaseSeeder::class);
        $this->seed(\Revys\RevyAdmin\Database\Seeds\DatabaseSeeder::class);
    }

    /** @test */
    public function every_page_from_admin_menu_can_be_accessed()
    {
        AdminMenu::all()->each(function (AdminMenu $item) {
            $this->get($item->getPath())->assertSuccessful();
            GlobalsComposer::flushData();
        });
    }
}