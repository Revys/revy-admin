<?php

namespace Revys\RevyAdmin\Tests;

use Revys\RevyAdmin\App\Http\Controllers\Controller;

class TestEntityController extends Controller
{
    protected $model = TestEntity::class;

    public static function listFieldsMap()
    {
        return [
            [
                'field' => 'string_field',
                'title' => __('Заголовок'),
                'link' => true
            ],
            [
                'field' => 'created_at',
                'title' => __('Создан')
            ],
            [
                'field' => 'updated_at',
                'title' => __('Изменён')
            ]
        ];
    }


    public static function editFieldsMap()
    {
        return [];
    }
}
