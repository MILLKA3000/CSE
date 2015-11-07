<?php

namespace App\Http\Controllers\Admin;

use App\Grades;
use App\GradesFiles;
use App\Logs;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RecheckGradesController extends Controller
{

    protected $about_module;

    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Inspektor');
        view()->share('type', 'recheck');
    }


    public function examGrade($id)
    {
        $this->about_module = GradesFiles::where('file_info_id',$id)->get();

        foreach($this->about_module as $module){
            $module->students = Grades::where('grade_file_id',$module->id)->get();
        }
        $about_module = $this->about_module;
        return view('admin.recheck.edit_add', compact('about_module'));
    }

    public function saveGrade(Request $request)
    {
        $grade = Grades::find($request['id']);
        $originalGrade = $grade->exam_grade;
        $dataModule = GradesFiles::where('file_info_id',$grade->grade_file_id)->get()->first();
        $grade->exam_grade = $request['value'];
        if($originalGrade!=$grade->exam_grade){
            if($grade->save()){
                Logs::_create('Recheck exams '. $dataModule->NameModule.' for student: '.$grade->fio.'. FROM '.$originalGrade.' TO '.$grade->exam_grade);
                return "true";
            }
            return "false";
        }

    }
}
