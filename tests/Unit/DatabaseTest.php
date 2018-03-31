<?php

namespace Revys\RevyAdmin\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Revys\RevyAdmin\Tests\TestCase;
use Schema;

class DatabaseTest extends TestCase
{
    use DatabaseMigrations;

    public function testGeneralTables()
    {
        $this->assertTrue(Schema::hasTable('admin_menu'));
        $this->assertTrue(Schema::hasTable('admin_menu_translations'));

        $this->assertTrue(Schema::hasTable('textblock'));
        $this->assertTrue(Schema::hasTable('textblock_translations'));

        $this->assertTrue(Schema::hasTable('navigation'));
        $this->assertTrue(Schema::hasTable('navigation_translations'));

        $this->assertTrue(Schema::hasTable('users'));
    }
}
