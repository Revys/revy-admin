<?php

namespace Revys\RevyAdmin\App;

use Revys\Revy\App\Entity;
use Revys\Revy\App\Traits\Translatable;
use Revys\Revy\App\Traits\Tree;

class AdminMenuBase extends Entity
{
    use Translatable, Tree;

    public static $translatedAttributes = ['title'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_menu';

    public static function rules()
    {
        return [
            'title'      => 'required',
            'controller' => 'required',
            'action'     => 'required',
        ];
    }

    private static function clearNavigationCache()
    {
        \Cache::forget('admin::navigation_left');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            self::clearNavigationCache();
        });

        static::updated(function () {
            self::clearNavigationCache();
        });

        static::deleted(function () {
            self::clearNavigationCache();
        });

        static::saved(function () {
            self::clearNavigationCache();
        });
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return route("admin::path", [$this->controller, $this->action]);
    }
}
