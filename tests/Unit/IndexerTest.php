<?php

namespace Revys\RevyAdmin\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Revys\RevyAdmin\Tests\TestCase;
use Revys\RevyAdmin\App\Helpers\Routes;
use Revys\RevyAdmin\App\Indexer;
use Schema;

class IndexerTest extends TestCase
{
    /** @test */
    public function mapping_class_by_name()
    {
        $indexer = app(Indexer::class);

        $baseClass = 'Some\Class';

        $indexer->mapClass('someclass', $baseClass);

        $class = $indexer->getMappedClass('someclass');

        $this->assertEquals('\\' . $baseClass, $class);
    }
}
