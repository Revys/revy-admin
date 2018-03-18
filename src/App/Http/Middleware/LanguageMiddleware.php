<?php

namespace Revys\RevyAdmin\App\Http\Middleware;

use Closure;
use App;
use Revys\Revy\App\Language;
use Revys\RevyAdmin\App\Providers\ComposerServiceProvider;
use Illuminate\Support\Facades\Route;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = request()->segment(2);

        $language = Language::findByCode($locale);

        if ($language)
            App::setLocale($language->code);

        // $composer = new ComposerServiceProvider(app());
        // $composer->registerGlobals();

        return $next($request);
    }
}
