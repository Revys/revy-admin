<?php

namespace Revys\RevyAdmin\App;

use Request;
use Illuminate\Support\Facades\Session;
use Revys\RevyAdmin\App\Providers\RevyAdminServiceProvider;

class RevyAdminBase
{
	public static function getPackagePath($path = '')
	{
		return RevyAdminServiceProvider::$packagePath . ($path != '' ? $path : '');
	}
	
	public static function getPackageAlias()
	{
		return RevyAdminServiceProvider::$packageAlias;
	}

	public static function layout($name)
	{
		return (Request::ajax() ? 'admin::layouts.ajax' : 'admin::layouts.' . $name);
	}

	public static function isTranslationMode()
	{
        return Session::get('admin::translation_mode', false);
	}

	public static function getTranslationFieldName($field, $language_code)
	{
		return $field . '__' . $language_code;
	}
}
