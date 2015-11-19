<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller {

    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Inspektor,Dekanat,Chief,Teacher');
        view()->share('type', '');
    }

	public function index()
	{
        $title = "Dashboard";

		return view('admin.dashboard.index',compact('title'));
	}
}