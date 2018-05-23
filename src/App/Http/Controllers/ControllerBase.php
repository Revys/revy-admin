<?php

namespace Revys\RevyAdmin\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Response;
use Revys\Revy\App\Traits\WithImages;
use View;
use Revys\RevyAdmin\App\Http\Composers\GlobalsComposer;
use Revys\RevyAdmin\App\Alerts;
use Revys\RevyAdmin\App\RevyAdmin;
use Revys\RevyAdmin\App\Helpers\Html\ActivePanel;
use Illuminate\Pagination\Paginator;
use Revys\RevyAdmin\App\AdminMenu;
use Revys\Revy\App\Entity;
use Revys\Revy\App\Language;
use Revys\RevyAdmin\App\MessagesBase;

class ControllerBase extends Controller
{
    protected $controller;
    protected $model;
    public $view_routes;
    public $actions = [
        'create'  => true,
        'edit'    => true,
        'hide'    => true,
        'publish' => true,
        'order'   => true
    ];

    public function __construct()
    {
        $this->controller = GlobalsComposer::getControllerName();
        $this->model = $this->model ?: '\Revys\RevyAdmin\App\\' . studly_case($this->controller);
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    public function getViewRoute($action)
    {
        $view = \RevyAdmin::getPackageAlias() . '::' . $this->getController() . '.' . $action;

        if (View::exists($view)) {
            return $view;
        }

        return (isset($this->view_routes[$action]) ? $this->view_routes[$action] : \RevyAdmin::getPackageAlias() . '::default.' . $action);
    }

    public function view($action = 'index', $data = [])
    {
        $result = View::make($this->getViewRoute($action), $data);

        if (\Request::ajax()) {
            $sections = $result->renderSections();

            if (count($sections) == 0) {
                $sections['content'] = $result->render();
            }

            $jsResult = View::make(\RevyAdmin::getPackageAlias() . '::js.ajax', $data);
            $js = $jsResult->render();

            $sections['js'] = ($sections['js'] ?? '') . $js;

            $alerts = $this->prepareAlerts();

            return [
                'content' => $sections['content'],
                'js'      => (isset($sections['js']) ? str_replace(['<script>', '</script>'], '', $sections['js']) : ''),
                'alerts'  => $alerts
            ];
        }

        return $result;
    }

    /**
     * @param array $data
     * @param array $additional
     * @return array
     */
    public function ajax($data = [], $additional = [])
    {
        $content = [];

        if ($data instanceof Arrayable)
            $data = $data->toArray();

        $content = array_merge($data, $additional);

        if (! isset($content['redirect']))
            $content['alerts'] = $this->prepareAlerts();

        return $content;
    }

    public function ajaxWithCode($httpCode, $data = [], $additional = [])
    {
        return response()->json($this->ajax($data, $additional), $httpCode);
    }

    public function prepareAlerts()
    {
        $alerts = Alerts::all();

        Alerts::clear();

        return $alerts;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        $data['fields'] = static::listFieldsMap();

        $data['items'] = $this->getModel()::paginate(50);

        $data = static::normalizeListData($data);

        return $this->view('index', $data);
    }

    public static function paginated($items)
    {
        return ($items instanceof \Illuminate\Pagination\LengthAwarePaginator);
    }

    public static function listFieldsMap()
    {
        return [
            [
                'field' => 'title',
                'title' => __('Заголовок'),
                'link'  => true
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $object = $this->getModel()::findOrFail($id);

        $fieldsMap = static::editFieldsMap();

        $activePanel = $this->editActivePanel($object);

        $data = compact('object', 'fieldsMap', 'activePanel');

        $data = $this->normalizeEditData($data);

        return $this->view('edit', $data);
    }

    public function normalizeEditData(array $data)
    {
        if (RevyAdmin::isTranslationMode()) {
            foreach ($data['fieldsMap'] as &$fieldsGroup) {
                if (isset($fieldsGroup['fields'])) {
                    foreach ($fieldsGroup['fields'] as $key => &$field) {
                        if ($this->getModel()::isTranslatableField($field['field'])) {
                            $field['translatable'] = true;
                        }
                    }
                }
            }
        }

        return $data;
    }

    public static function normalizeListData($data)
    {
        return $data;
    }

    public static function editActionsMap()
    {
        return [
            'save'   => [
                'label'  => __('admin::buttons.save'),
                'style'  => 'success',
                'method' => 'PUT',
                'type'   => 'submit',
                'href'   => function ($controller, $object) {
                    return route('admin::update', [$controller, optional($object)->id]);
                }
            ],
            'delete' => (GlobalsComposer::getAction() !== 'create' ? [
                'label'  => '<i class="icon icon--delete"></i>',
                'style'  => 'danger',
                'method' => 'DELETE',
                'href'   => function ($controller, $object) {
                    return route('admin::delete', [$controller, $object->id]);
                }
            ] : false),
            'back'   => [
                'label' => __('admin::buttons.back'),
                'style' => 'default',
                'href'  => function ($controller, $object) {
                    return route('admin::list', [$controller]);
                }
            ]
        ];
    }

    /*
     * @todo Export, Copy, View buttons
     */
    public function editActivePanel($object)
    {
        // $activePanel = new ActivePanel('edit', $object);
        return [
            'caption' => $object->title,
            'buttons' => [
                'back' => true,
                // 'export' => true,
                // 'copy' => true,
                // 'view' => true
            ]
        ];
    }

    public function createActivePanel()
    {
        $section = AdminMenu::where('controller', '=', $this->getController())->orderBy('parent_id', 'asc')->first();

        return [
            'caption' => __('Добавить') . ($section != false ? ' в <b>' . $section->title . '</b>' : ''),
            'buttons' => [
                'back' => true
            ]
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fieldsMap = static::editFieldsMap();

        $activePanel = $this->createActivePanel();

        $data = compact('fieldsMap', 'activePanel');

        $data = $this->normalizeCreateData($data);

        return $this->view('create', $data);
    }

    public function normalizeCreateData(array $data)
    {
        if (RevyAdmin::isTranslationMode()) {
            foreach ($data['fieldsMap'] as &$fieldsGroup) {
                if (isset($fieldsGroup['fields'])) {
                    foreach ($fieldsGroup['fields'] as $key => &$field) {
                        if ($this->getModel()::isTranslatableField($field['field'])) {
                            $field['translatable'] = true;
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Create the specified resource in storage.
     *
     * @return array
     */
    public function insert()
    {
        $request = request();

        $model = $this->getModel();

        $this->validate($request, $model::getRules(), $model::messages());

        $data = $this->prepareCreateData($request->all());

        $object = $model::create($data);

        $this->updateImages($object, $data);

        Alerts::success(__('admin::alerts.added'));

        $redirect = route('admin::edit', [$this->getController(), $object->id]);

        return $this->ajax($object, compact('redirect'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return array
     */
    public function update($id)
    {
        $request = request();

        $model = $this->getModel();

        $this->validate($request, $model::getRules(), $model::messages());

        $object = $model::findOrFail($id);

        $data = $this->prepareUpdateData($object, $request->all());

        $object->update($data);

        Alerts::success(__('admin::alerts.saved'));

        return $this->ajax($object);
    }

    /**
     * @param Entity $object
     * @param array $data
     * @return array
     */
    public function prepareUpdateData($object, $data)
    {
        if (isset($object->status))
            $data['status'] = isset($data['status']) ? (bool) $data['status'] : false;

        $model = $this->getModel();

        if (RevyAdmin::isTranslationMode() && $model::translatable()) {
            $languages = Language::getLanguagesAll();
            foreach ($model::$translatedAttributes as $field) {
                foreach ($languages as $language) {
                    if (! isset($data[$field . '__' . $language->code])) {
                        continue;
                    }

                    $data[$language->code][$field] = $data[$field . '__' . $language->code];
                    unset($data[$field . '__' . $language->code]);
                }
            }
        }

        $this->updateImages($object, $data);

        return $data;
    }

    public function prepareCreateData($data)
    {
        $this->prepareFiles($data);

        if (array_key_exists('status', $data))
            $data['status'] = isset($data['status']) ? (bool) $data['status'] : false;

        $model = $this->getModel();

        if (RevyAdmin::isTranslationMode() && $model::translatable()) {
            $languages = Language::getLanguagesAll();
            foreach ($model::$translatedAttributes as $field) {
                foreach ($languages as $language) {
                    if (! isset($data[$field . '__' . $language->code])) {
                        continue;
                    }

                    $data[$language->code][$field] = $data[$field . '__' . $language->code];
                    unset($data[$field . '__' . $language->code]);
                }
            }
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response|array
     */
    public function delete($id)
    {
        $this->getModel()::find($id)->delete();

        Alerts::success(__('Удаление прошло успешно'));

        if (request()->expectsJson()) {
            return ['redirect' => route('admin::list', [$this->getController()])];
        }

        return redirect()->route('admin::list', [$this->getController()]);
    }

    /**
     * Publish the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function publish($id)
    {
        $this->getModel()::find($id)->publish();

        Alerts::success(__('Объект опубликован'));

        return redirect()->route('admin::edit', [$this->getController(), $id]);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function fastDelete()
    {
        \Revy::assertAjax();

        $items = request()->input('items');

        if (is_array($items)) {
            $model = $this->getModel();

            foreach ($items as $id) {
                $model::find($id)->delete();
            }

            Alerts::success(__('Удаление прошло успешно'));
        } else {
            Alerts::fail(__('Не выбрано ни одного элемента'));
        }

        return $this->index();
    }

    /**
     * Publish the specified resources from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function fastPublish()
    {
        \Revy::assertAjax();

        $items = request()->input('items');

        if (is_array($items)) {
            $model = $this->getModel();

            foreach ($items as $id) {
                $model::find($id)->publish();
            }

            Alerts::success(__('Объекты опубликованы'));
        } else {
            Alerts::fail(__('Не выбрано ни одного элемента'));
        }

        return $this->index();
    }

    /**
     * Hide the specified resources from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function fastHide()
    {
        \Revy::assertAjax();

        $items = request()->input('items');

        if (is_array($items)) {
            $model = $this->getModel();

            foreach ($items as $id) {
                $model::find($id)->hide();
            }

            Alerts::success(__('Объекты скрыты'));
        } else {
            Alerts::fail(__('Не выбрано ни одного элемента'));
        }

        return $this->index();
    }

    /**
     * @param Entity|WithImages $object
     * @param array $data
     */
    public function updateImages($object, &$data)
    {
        $files = request()->allFiles();

        if (isset($files['image']) and isset($data['image'])) {
            $object->images()->removeAll();

            if (is_array($files['image'])) {
                $files['image'] = current($files['image']);
            }
        }

        foreach ($files as $fieldName => $fileData) {
            unset($data[$fieldName]);

            if (is_array($fileData)) {
                foreach ($fileData as $file) {
                    $object->images()->add($file);
                }
                continue;
            }

            $object->images()->add($fileData);
        }
    }

    public function removeImage()
    {
        $request = request();
        $object_id = $request->input('object_id');
        $filename = $request->input('filename');

        $object = $this->getModel()::findOrFail($object_id);

        return $object->images()->remove($filename);
    }

    private function prepareFiles(&$data)
    {
        $files = request()->allFiles();

        foreach ($files as $fieldName => $fileData) {
            unset($data[$fieldName]);
        }
    }
}
