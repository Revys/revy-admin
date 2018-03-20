<?php

namespace Revys\RevyAdmin\App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\Response;
use Revys\RevyAdmin\App\Alerts;
use Revys\RevyAdmin\App\User;

class AuthControllerBase extends ControllerBase
{
    public function login()
    {
        if (Auth::check())
            return redirect(route('admin::home'));

        return \View::make('admin::auth.login');
    }

    public function signin(Request $request)
    {
        $id = $request->input('id');
        $password = $request->input('password');
        $remember = $request->input('remember');
        $redirect = $request->input('redirect');

        if (
            ! Auth::attempt(['login' => $id, 'password' => $password], $remember) and
            ! Auth::attempt(['email' => $id, 'password' => $password], $remember)
        ) {
            Alerts::fail(__('Неверный логин или пароль'));

            return $this->ajaxWithCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! Auth::user()->isAdmin()) {
            Auth::logout();

            Alerts::fail(__('У вас недостаточно прав'));

            return $this->ajaxWithCode(Response::HTTP_FORBIDDEN);
        }

        return $this->ajax([], compact('redirect'));
    }

    public function logout()
    {
        Auth::logout();

        return \Redirect::back();
    }
}
