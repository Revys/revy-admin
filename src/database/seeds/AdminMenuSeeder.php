<?php

namespace Revys\RevyAdmin\Database\Seeds;

use Illuminate\Database\Seeder;
use Revys\RevyAdmin\App\AdminMenu;

class AdminMenuSeeder extends Seeder
{
    public function run()
    {
        $this->createItem('Админ меню', 'admin_menu', 'index', 'fa-align-justify');
        $this->createItem('Языки', 'language', 'index', 'fa-globe');

        \Cache::forget('admin::navigation_left');

    }

    private function createItem($title, $contoller, $action = 'index', $icon = '', $parent_id = null)
    {
        return AdminMenu::create([
            'title' => $title,
            'controller' => $contoller,
            'action' => $action,
            'icon' => $icon,
            'parent_id' => $parent_id
        ]);
    }
}