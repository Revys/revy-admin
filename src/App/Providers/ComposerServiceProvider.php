<?php

namespace Revys\RevyAdmin\App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use App;

class ComposerServiceProvider extends ServiceProvider
{
	public function register()
	{
		View::composer(
            'admin::*',
			'Revys\RevyAdmin\App\Http\Composers\GlobalsComposer'
        );

		View::composer(
            ['admin::navigation.left'], 
			'Revys\RevyAdmin\App\Http\Composers\NavigationComposer'
        );

		View::composer(
            ['admin::navigation.top'], 
			'Revys\RevyAdmin\App\Http\Composers\NavigationComposer'
        );

		View::composer(
            ['admin::modules.alerts'], 
			'Revys\RevyAdmin\App\Http\Composers\AlertsComposer'
        );
	}
}
