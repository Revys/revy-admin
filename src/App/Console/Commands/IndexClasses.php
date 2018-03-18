<?php

namespace Revys\RevyAdmin\App\Console\Commands;

use Illuminate\Console\Command;
use Revys\RevyAdmin\App\Indexer;

class IndexClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index-classes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index additional classes, added by developer of the app';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $indexer = app()->make(Indexer::class);
        $indexer->index();
    }
}
