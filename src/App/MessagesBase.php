<?php

namespace Revys\RevyAdmin\App;

class MessagesBase
{
    public static $messages;

    public static function get($key)
    {
        if (self::$messages === null)
            self::$messages = self::messages();

        if (isset(self::$messages[$key]))
            return self::$messages[$key];

        return $key;
    }

    public static function messages()
    {
        return [
            'saved' => __('Сохранение прошло успешно')
        ];
    }
}
