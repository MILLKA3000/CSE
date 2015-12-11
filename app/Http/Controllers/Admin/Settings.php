<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class Settings extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Inspektor');
        view()->share('type', 'settings');
    }

    public function index()
    {
        $data = Setting::all()->lists('value','key')->toArray();
        return view("admin.setting.index", compact('data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        foreach($request->request as $keyRequest=>$req){
            $setting = Setting::where('key',$keyRequest)->get()->first();
            if($setting!=null){
                $setting->update(['value'=>$req]);
            }

        }
        return redirect('/settings')->with('success','Setting Saved...');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache()
    {
        Cache::flush();
        return redirect('/settings')->with('success','Cache cleared...');
    }


    public function toSessionDate(Request $request){
        $request->session()->put('date', $request['date']);
    }

}
