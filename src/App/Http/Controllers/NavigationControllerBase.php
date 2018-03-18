<?php

namespace Revys\RevyAdmin\App\Http\Controllers;

use Illuminate\Http\Request;
use Revys\RevyAdmin\App\Navigation;

class NavigationControllerBase extends Controller
{
    public static function listFieldsMap()
    {
        return [
            [
                'field' => 'title',
                'title' => __('Заголовок'),
                'link' => true
            ]
        ];
    }

    public static function editFieldsMap()
    {
        return [
            [
                'caption' => __('Базовая информация'),
                'actions' => self::editActionsMap(),
                'fields' => [
                    [
                        'type' => 'string',
                        'label' => __('Заголовок'),
                        'field' => 'title',
                        'value' => 'title'
                    ],
                    [
                        'type' => 'parent',
                        'label' => __('Родитель'),
                        'field' => 'parent_id',
                        'value' => 'parent_id',
                        'values' => function($object) { 
                            return Navigation::getListForRelation($object, 'id', 'title');
                        }
                    ],
                    [
                        'type' => 'bool',
                        'label' => __('Опубликован'),
                        'field' => 'status',
                        'value' => 'status'
                    ]
                ]
            ]
        ];
    }
}
