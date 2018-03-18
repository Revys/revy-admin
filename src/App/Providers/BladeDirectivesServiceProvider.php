<?php

namespace Revys\RevyAdmin\App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use Request;
use Revys\RevyAdmin\App\RevyAdmin;

class BladeDirectivesServiceProvider extends ServiceProvider
{
	public function register()
	{
		Blade::directive('includeDefault', function ($expression) {
			return "<?php echo \$__env->make(\$controller->getViewRoute($expression), array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>";
		});

		Blade::if('translation_mode', function () {
			return RevyAdmin::isTranslationMode();
		});

		Blade::directive('controller', function ($expression) {
			$vars = explode(',', $expression);
			$action = array_shift($vars);
			$action = str_replace(['"', '\''], "", $action);
			return "<?php echo \$controller->{$action}(" . implode(',', $vars) . "); ?>";
		});
	}
}
