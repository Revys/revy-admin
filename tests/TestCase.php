<?php

namespace Revys\RevyAdmin\Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Revys\RevyAdmin\App\Http\Composers\GlobalsComposer;
use Revys\RevyAdmin\App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();

        $pathToFactories = base_path('vendor/revys/revy-admin/src/database/factories');

        $this->app->make('Illuminate\Database\Eloquent\Factory')->load($pathToFactories);

        GlobalsComposer::flushData();

        $this->disableExceptionHandling();

    }

    protected function signIn($user = null, $admin = true)
    {
        $user = $user ?: create(User::class, [
            'admin' => $admin
        ]);

        $this->actingAs($user);

        return $this;
    }


    // Hat tip, @adamwathan.
    protected function disableExceptionHandling()
    {
        $this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);
        $this->app->instance(ExceptionHandler::class, new class extends Handler
        {
            public function __construct()
            {
            }

            public function report(\Exception $e)
            {
            }

            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }

    protected function withExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, $this->oldExceptionHandler);
        return $this;
    }
}