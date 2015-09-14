<?php

namespace App\Http\Controllers\Excel;

use App\Logs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helper\File as file;
use App\Helper\Excel_;


class ExcelController extends Controller
{

    private $url;

    private $data;

    public function __construct()
    {
        $this->middleware('role:Admin');
        view()->share('type', 'work');
    }


    /**
     * parce xls
     */
    public function importXLS(Request $request){

        if ($file = $request->file('xls')) {

            if (array_keys([
                'application/vnd.ms-office',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],$file->getMimeType())) {

                $this->url = $file->move('xls/'.file::_get_path(), $file->getClientOriginalName());

                $this->data = Excel_::_loadXls($this->url);



                return view('admin.excel.viewDataFromXls',['data'=>$this->data]);
            } else {
                return view('admin.excel.import')->with(['error'=>'No type file']);
            }
        }else{
            return view('admin.excel.import');
        }

    }

}
