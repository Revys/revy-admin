<?php

namespace Revys\RevyAdmin\App;

use Session;
use Revys\RevyAdmin\App\Messages;

class AlertsBase
{
    protected static $prefix = 'alerts';

    public static function alert($message, $tag = 'default', $time = 3)
    {
        $alerts = array();

        if (substr($message, 1) !== '@')
            $message = Messages::get($message);

        $alert = array(
            'message' => $message,
            'tag' => $tag,
            'time' => $time
        );

        $alerts[] = $alert;

        if (Session::get(self::$prefix)) 
            $alerts = array_merge($alerts, Session::flash(self::$prefix));

        Session::flash(self::$prefix, $alerts);
    }
    
    public static function success($message, $time = 3)
    {
        return self::alert($message, 'success', $time);
    }
    
    public static function fail($message, $time = 3)
    {
        return self::alert($message, 'danger', $time);
    }
    
    public static function warning($message, $time = 3)
    {
        return self::alert($message, 'warning', $time);
    }
    
    public static function info($message, $time = 3)
    {
        return self::alert($message, 'info', $time);
    }

    public static function all()
    {
        if (! Session::get(self::$prefix)) 
            return array();

        return Session::get(self::$prefix);
    }

    public static function clear()
    {
        return Session::forget(self::$prefix);
    }
}
