<?php

namespace Revys\RevyAdmin\App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Revys\Revy\App\Helpers\Tree;
use Revys\RevyAdmin\App\AdminMenu;

class AdminMenuControllerBase extends Controller
{
    public static function listFieldsMap()
    {
        return [
			[
				'field' => 'title',
				'title' => __('Заголовок'),
				'link' => true
			],
			[
				'field' => 'controller',
				'title' => __('Контроллер')
			],
			[
				'field' => 'action',
				'title' => __('Действие')
			]
		];
    }

    public static function normalizeListData($data)
    {
        $items = Tree::sort($data['items']);

        $data['items'] = new Collection($items);

        $data['tree'] = true;

        return parent::normalizeListData($data);
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
                        'type' => 'string',
                        'label' => __('Контроллер'),
                        'field' => 'controller',
                        'value' => 'controller'
                    ],
                    [
                        'type' => 'string',
                        'label' => __('Действие'),
                        'field' => 'action',
                        'value' => 'action'
                    ],
                    [
                        'type' => 'icon',
                        'label' => __('Иконка'),
                        'field' => 'icon',
                        'value' => 'icon'
                    ],
                    [
                        'type' => 'parent',
                        'label' => __('Родитель'),
                        'field' => 'parent_id',
                        'value' => 'parent_id',
                        'values' => function($object) { 
                            return AdminMenu::getListForRelation($object, 'id', 'title');
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
