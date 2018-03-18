<?php

namespace Revys\RevyAdmin\App\Http\Composers;

use Revys\RevyAdmin\App\AdminMenu;
use Revys\Revy\App\Textblock;
use Revys\Revy\App\Menu;
use Illuminate\Support\Facades\Cache;

class NavigationComposer
{
    protected $menu;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose($view)
    {
        $tmp = explode('.', $view->name());
        $method = end($tmp);

        $this->$method($view);
    }

    public function left($view)
    {
        $items = Cache::rememberForever('admin::navigation_left', function() {
            return AdminMenu::treePublished(2)->withTranslation()->get();
        });

        $view->with(compact('items'));
    }

    public function top($view)
    {
        $controller = GlobalsComposer::getController();
        $action = GlobalsComposer::getAction();

        $path = $this->getPath($controller, $action);

        $actions = $controller->actions;

        $view->with(compact('path', 'action', 'actions'));
    }

    public function getPath($controller, $action)
    {
        $path = [];

        $admmenu = AdminMenu::where([
            ['controller', '=', $controller->getController()],
            ['action', '=', GlobalsComposer::getAction()]
        ])->orderBy('parent_id', 'asc')->first();

        if (! $admmenu)
            $admmenu = AdminMenu::where('controller', '=', $controller->getController())->orderBy('parent_id', 'asc')->first();

        if (! $admmenu)
            return;

        $tree = $admmenu->parent()->get();

        foreach ($tree as $item) {
            $path[] = $item->title;
        }
        
        $path[] = $admmenu->title;

        switch ($action) {
            case 'edit':
                $path[] = __("Редактирование");
                break;
            case 'create':
                $path[] = __("Добавление");
                break;
        }

        return $path;
    }
}