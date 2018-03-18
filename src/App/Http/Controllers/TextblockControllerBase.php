<?php

namespace Revys\RevyAdmin\App\Http\Controllers;

use Revys\RevyAdmin\App\Textblock;
use Illuminate\Http\Request;

class TextblockControllerBase extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->model = '\Revys\Revy\App\\'.studly_case($this->controller);
    }

    public static function listFieldsMap()
    {
        return [
			[
				'field' => 'title',
				'title' => __('Заголовок'),
				'link' => true
            ],
			[
				'field' => 'name_id',
				'title' => __('Идентификатор')
            ],
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
                        'type' => 'string',
                        'label' => __('Идентификатор'),
                        'field' => 'name_id',
                        'value' => 'name_id'
                    ],
                    [
                        'type' => 'text',
                        'label' => __('Текст'),
                        'field' => 'text',
                        'value' => 'text',
                        'size' => 'large'
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
