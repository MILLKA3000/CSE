<?php

namespace App\Http\Controllers\Admin;

use App\Logs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use yajra\Datatables\Datatables;

class LogsController extends Controller
{
    function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Chief');
        view()->share('type', 'logs');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.logs.index');
    }

    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $users = Logs::select('users.name', 'logs.title','logs.created_at','logs.id', 'logs.user_id')->join('users','users.id','=','logs.user_id')->get();

        return Datatables::of($users)
            ->remove_column('logs.id')
            ->remove_column('users.id')
            ->make();
    }
}
