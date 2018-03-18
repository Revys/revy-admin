<?php

namespace Revys\RevyAdmin\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Revys\Revy\Tests\TestCase;
use Revys\RevyAdmin\App\Helpers\Routes;
use Revys\RevyAdmin\App\Indexer;
use Schema;

class RoutesTest extends TestCase
{
    /** @test */
    public function get_controller_mapped_class_by_controller_name()
    {
        $class = Routes::getAdminControllerClassName('admin_menu');

        $this->assertEquals('\Revys\RevyAdmin\App\Http\Controllers\AdminMenuController', $class);
    }
}