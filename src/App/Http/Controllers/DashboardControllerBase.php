<?php

namespace Revys\RevyAdmin\App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardControllerBase extends Controller
{
	public $actions = [];
	
	public function index()
	{
		return \View::make('admin::dashboard.index');
	}
}