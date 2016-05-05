<?php

namespace App\Http\Controllers\Excel;

use App\Logs;
use App\TypeExam;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helper\File as file;
use App\Helper\Excel_;
use App\Model\Excel\Excel as Model_Excel;
use Illuminate\Support\Facades\Session;


class ExcelController extends Controller
{

    private $url;

    private $data;

    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin');
        view()->share('type', 'work');
    }


    /**
     * parce xls
     */
    public function importXLS(Request $request){
        if ($file = $request->file('xls')) {
            if ($request->get('qtyQuestions')==0){return view('admin.excel.import',['type_exam'=>TypeExam::all()])->with(['error'=>'Виберіть кількість питань на предмет']);}
            if (array_keys([
                'application/vnd.ms-office',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],$file->getMimeType())) {
                Session::forget('date');
                $this->otherData['path'] = $file->move('xls'.DIRECTORY_SEPARATOR.file::_get_path(), $file->getClientOriginalName());
                $this->otherData['urlOriginalName'] = $file->getClientOriginalName();
                $this->otherData['qtyQuestions'] = $request->get('qtyQuestions');
                $this->data = Excel_::_loadXls($this->otherData['path']);
                $Model_Excel = new Model_Excel($this->data,$this->otherData);
                $message = $Model_Excel->SaveData();
                return view('admin.excel.viewDataFromXls',[
                    'data'=>$this->data,
                    'message'=>$message,
                    'id_file'=>(isset($Model_Excel->id_file->id))?$Model_Excel->id_file->id:false
                ]);
            } else {
                return view('admin.excel.import',['type_exam'=>TypeExam::all()])->with(['error'=>'No type file']);
            }
        }else{
            return view('admin.excel.import',['type_exam'=>TypeExam::all()]);
        }

    }

}
