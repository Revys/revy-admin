<?php

namespace Revys\RevyAdmin\App\Http\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Revys\Revy\App\Language;
use Revys\Revy\App\Revy;
use Revys\RevyAdmin\App\Helpers\RoutesBase;

class GlobalsComposer
{
    protected static $data;

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose()
    {
        self::$data = [
            'locale' => Revy::getLanguage(),
            'languages' => Language::getLanguagesAll(),
            'user' => Auth::user(),
            'controller' => self::getController(),
            'controller_name' => self::getControllerName(),
            'action' => self::getAction(),
            'uri' => request()->path()
        ];

        View::share(self::$data);
    }

    public static function get($key)
    { 
        return self::$data[$key];
    }

    public static function flushData()
    {
        self::$data = null;
    }

    public static function getControllerName()
    {
        if (isset(self::$data['controller_name']))
            return self::$data['controller_name'];

        $controller = request()->route('controller');

        if ($controller == '') {
            if (isset(request()->route()->action['controller'])) {
                list($class, $action) = explode('@', request()->route()->action['controller']);
                $controller = snake_case(str_replace('Controller', '', class_basename($class)));

                self::$data['action'] = $action;
            }
        }

        return $controller;
    }

    public static function getController()
    {
        if (isset(self::$data['controller']))
            return self::$data['controller'];

        $controller = self::getControllerName();

        return app()->make(RoutesBase::getAdminControllerClassName($controller));
    }

    public static function getAction()
    {
        if (isset(self::$data['action']))
            return self::$data['action'];

        $action = request()->route('action');

        if ($action == '' and request()->route() !== null) {
            list($prefix, $action) = explode('::', request()->route()->action['as']);
        }

        return $action;
    }
}