<?php

namespace Revys\RevyAdmin\App\Helpers;

use Revys\RevyAdmin\App\Indexer;

class RoutesBase
{
    /**
     * @param string $controller
     * @return string
     */
    public static function getAdminControllerClassName($controller)
    {
        $class = app(Indexer::class)->getMappedClass($controller . '_controller');

        if ($class)
            return $class;

        return '\Revys\RevyAdmin\App\Http\Controllers\\' . studly_case($controller) . 'Controller';
    }
}