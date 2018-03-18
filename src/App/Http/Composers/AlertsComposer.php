<?php

namespace Revys\RevyAdmin\App\Http\Composers;

use Revys\RevyAdmin\App\Alerts;

class AlertsComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose($view)
    {
        $alerts = Alerts::all();

        $view->with(compact('alerts'));
    }
}