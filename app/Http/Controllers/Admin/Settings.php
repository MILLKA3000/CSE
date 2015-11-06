<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class Settings extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin');
        view()->share('type', 'setting');
    }

    public function index()
    {
        return view("admin.setting.index");
    }

    public function clearCache()
    {
        Cache::flush();
        return redirect()->back();
    }

}
