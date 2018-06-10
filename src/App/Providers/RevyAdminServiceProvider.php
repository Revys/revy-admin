<?php

namespace Revys\RevyAdmin\App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Revys\RevyAdmin\App\Http\Middleware\BaseMiddleware;
use Revys\RevyAdmin\App\Http\Middleware\LanguageMiddleware;
use Revys\RevyAdmin\App\Indexer;
use Revys\RevyAdmin\App\RevyAdmin;
use Illuminate\Support\ServiceProvider;
use Revys\RevyAdmin\App\Translations;

class RevyAdminServiceProvider extends ServiceProvider
{
    public static $packagePath = __DIR__ . '/../../';
    public static $packageAlias = 'admin';

    public static function getPackageAlias()
    {
        return self::$packageAlias;
    }

    public static function getPackagePath()
    {
        return self::$packagePath;
    }

    /**
     * Bootstrap the application services.
     *
     * @param Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $this->load();

        $this->loadCommands();

        // Middlewares
        $router->aliasMiddleware(self::$packageAlias, BaseMiddleware::class);
        $router->aliasMiddleware(self::$packageAlias . '_lang', LanguageMiddleware::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(RevyAdmin::class);
        $this->app->singleton(Translations::class);
        $this->app->singleton(Indexer::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('RevyAdmin', RevyAdmin::class);
    }

    public function initProviders()
    {
        $this->app->register(ComposerServiceProvider::class);
        $this->app->register(BladeDirectivesServiceProvider::class);
    }

    public function load()
    {
        // Config
        $this->publishes([
            self::$packagePath . 'config/config.php' => config_path(self::$packageAlias . '/config.php'),
        ], 'config');

        $this->mergeConfigFrom(
            self::$packagePath . 'config/config.php',
            self::$packageAlias
        );

        // Routes
        $this->loadRoutesFrom(self::$packagePath . 'routes.php');

        // Views
        $this->loadViewsFrom(self::$packagePath . 'resources/views', self::$packageAlias);

        $this->publishes([
            self::$packagePath . 'resources/views' => base_path('resources/views/vendor/admin'),
        ], 'views');

        // Translations
        $this->loadTranslationsFrom(self::$packagePath . 'translations', self::$packageAlias);
        $this->loadJsonTranslationsFrom(self::$packagePath . 'translations');

        // Assets
        $this->publishes([
            self::$packagePath . 'resources/public' => public_path('admin-assets'),
        ], 'public');

        // Migrations
        $this->loadMigrationsFrom(self::$packagePath . 'database/migrations');
    }

    public function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'Revys\RevyAdmin\App\Console\Commands\IndexClasses',
            ]);
        }
    }
}
