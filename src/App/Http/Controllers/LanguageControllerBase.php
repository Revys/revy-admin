<?php

namespace Revys\RevyAdmin\App\Http\Controllers;

use Session;
use Revys\RevyAdmin\App\Alerts;
use Revys\RevyAdmin\App\Translations;
use Revys\Revy\App\Language;

class LanguageControllerBase extends Controller
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
				'field' => 'code',
				'title' => __('Код языка')
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
                        'type' => 'string',
                        'label' => __('Код языка'),
                        'field' => 'code',
                        'value' => 'code'
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
    
    public function toggleTranslationMode()
    {
        \Revy::assertAjax();

        $value = ! Session::get('admin::translation_mode');
        
        Session::put('admin::translation_mode', $value);
        
        if ($value)
            Alerts::success(__('Режим перевода включен'));
        else
            Alerts::alert(__('Режим перевода выключен'));

        return [
            'result' => $value
        ];
    }

    /**
     * @param int|string $language
     * @return array|\Illuminate\Contracts\View\View
     */
    public function translations($language)
    {
        $translation = app()->make(Translations::class);

        $language = Language::findOrFail($language);

        $translations = $translation->getAllTranslations(true, $language->code);

        return $this->view('translations', compact('translations'));
    }

    /**
     * @return array
     */
    public function saveTranslations()
    {
        \Revy::assertAjax();

        $data = request()->input();

        try {
            $group = $data['group'];
            $translations = $data['translations'];
            $language = $data['language'];
    
            if ($group == '')
                throw new \Exception("Can't save. Group is empty");
            if ($translations == '')
                throw new \Exception("Can't save. Translations are empty");
            if ($language == '')
                throw new \Exception("Can't save. Language is empty");
                
            
            $translation = app()->make(Translations::class);
    
            $translation->saveTranslations($group, $translations, $language);
            
            Alerts::success('saved');
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());
            Alerts::fail($ex->getMessage());
        }

		return $this->ajax();
    }

    /**
     * @return array|\Illuminate\Contracts\View\View
     */
    public function indexPhrases()
    {
        \Revy::assertAjax();

        $language = request()->input('language');

        try {
            $translation = app()->make(Translations::class);
    
            $language = Language::findOrFail($language);

            $translation->indexPhrases(null, $language->code);
            
            Alerts::success(__('Фразы успешно проиндексированы'));
        } catch (\Exception $ex) {
            \Log::error($ex);
            Alerts::fail($ex->getMessage());
        }

        return $this->translations($language->id);
    }
}
