<?php

namespace App\Http\Controllers\Admin;

use App\Grades;
use App\UserToDepartments;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConsultingGrades;
use App\User;
use App\CacheDepartment;
use App\CacheSpeciality;
use App\GradesFiles;
use Datatables;
use Auth;

class TeacherSetGrade extends Controller
{

    private $about_module;

    function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Teacher,Chief');
        view()->share('type', 'teacher');
    }

    public function index()
    {
       return view('admin.consulting.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($moduleVariant)
    {
        $this->about_module = GradesFiles::where('ModuleVariantID',$moduleVariant)->get()->first();
        $students = Grades::select('consulting_grades.id_student as stud_consult_grade',
        'consulting_grades.grade_consulting',
        'grades.id_student',
        'grades.fio',
        'grades.group',
        'grades.exam_grade',
        'grades.grade'
            )->
        where('grade_file_id',$this->about_module->id)
            ->leftjoin('consulting_grades', function($join)
            {
                $join->on('consulting_grades.id_student', '=', 'grades.id_student')
                    ->where('consulting_grades.id_num_plan','=',$this->about_module->ModuleVariantID);
            })

            ->orderBy('group', 'asc')
            ->orderBy('fio', 'asc')
            ->get();
        $about_module = $this->about_module;
        return view('admin.consulting.edit_add', compact('about_module','students'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveGrade(Request $request, ConsultingGrades $cons)
    {
        if(!is_int($request['value']) && $request['value']>20){
            return "false";
        }

        $check = $cons->where('id_student',$request['student'])->where('id_num_plan',$request['modnum'])->get()->first();

        if ($check) $cons->where('id',$check->id)->delete();
        if($request['value']!='') {
            if ($cons->create([
                'id_student' => $request['student'],
                'id_num_plan' => $request['modnum'],
                'grade_consulting' => $request['value'],
                'user_id' => Auth::user()->id
            ])
            ) {
                return "true";
            }
        }
        return "false";
    }


    public function data()
    {

        $allowDiscepline = UserToDepartments::where('user_id',Auth::user()->id)->join('allowed_discipline','allowed_discipline.departments_id','=','user_to_departament.departments_id')->get()->first();


        $grades = GradesFiles::select(array(
//            'grades_files.id',
            'grades_files.EduYear',
            'grades_files.Semester',
            'grades_files.DepartmentId',
            'grades_files.SpecialityId',
            'grades_files.NameDiscipline',
            'grades_files.NameModule',
            'grades_files.ModuleNum',
            'grades_files.ModuleVariantID',
            'grades_files.DisciplineVariantID',
        ))->distinct('ModuleVariantID');

        if(in_array(Auth::user()->role_id,[5]) && (isset($allowDiscepline->arrayAllowed))) {
            $grades->whereIn('DisciplineVariantID',(array)json_decode($allowDiscepline->arrayAllowed));
        }

        $grades->get();
        foreach($grades as $grade){
            $grade->DepartmentId = CacheDepartment::getDepartment($grade->DepartmentId)->name;
            $grade->SpecialityId = CacheSpeciality::getSpeciality($grade->SpecialityId)->name;
        }

        return Datatables::of($grades)
            ->edit_column('EduYear', '{{$EduYear}}/{{$EduYear+1}}')
            ->edit_column('NameModule', '{{$ModuleNum}}. {{$NameModule}}')
            ->add_column('actions','<a href="{{ URL::to(\'teacher/\' . $ModuleVariantID . \'/edit\' )}}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil"></span>  {{ trans("admin/modal.this") }}</a>')
            ->add_column('GetDocs','<a href="{{ URL::to(\'documents/\' . $ModuleVariantID . \'/getAllConsultingDocuments\' )}}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil"></span>  Get Docs</a>')
            ->remove_column('ModuleVariantID')
            ->remove_column('DisciplineVariantID')
            ->remove_column('ModuleNum')
            ->make();
    }
}