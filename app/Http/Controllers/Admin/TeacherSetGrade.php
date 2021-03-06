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
        $this->middleware('role:Admin,Self-Admin,Dekanat,Teacher,Chief');
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
    public function edit($depId,$moduleVariant)
    {
        $this->about_module = GradesFiles::where('ModuleVariantID',$moduleVariant)->where('DepartmentId', $depId)->get();

        $students = Grades::select('consulting_grades.id_student as stud_consult_grade',
        'consulting_grades.grade_consulting',
        'grades.id_student',
        'grades.fio',
        'grades.group',
        'grades.grade'
            )->
            whereIn('grade_file_id', (array)$this->about_module->lists('id')->toArray())
            ->leftjoin('consulting_grades', function($join)
            {
                $join->on('consulting_grades.id_student', '=', 'grades.id_student')
                    ->where('consulting_grades.id_num_plan','=',$this->about_module->first()->ModuleVariantID);
            })

            ->orderBy('group', 'asc')
            ->orderBy('fio', 'asc')
            ->distinct()
            ->get();
        $about_module = $this->about_module->first();
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
        $check = $cons->where('id_student',$request['student'])->where('id_num_plan',$request['modnum'])->get()->first();

        if(!is_int($request['value']) && $request['value']>20){
            return json_encode(['status'=>'false','message'=>trans("admin/modules/consulting.valueMoreThen20"),'grade'=>(isset($check->id))?$check->grade_consulting:'']);
        }

        if ($check) $cons->where('id',$check->id)->delete();
        if($request['value']!='') {
            if ($cons->create([
                'id_student' => $request['student'],
                'id_num_plan' => $request['modnum'],
                'grade_consulting' => $request['value'],
                'user_id' => Auth::user()->id,
                'department_id' => $request['depId']
            ])
            ) {
                return json_encode(['status'=>'true','message'=>trans("admin/modules/consulting.ok")]);
            }
        }
        return json_encode(['status'=>'false','message'=>trans("admin/modules/consulting.error")]);
    }

    public function clearGrade(Request $request, ConsultingGrades $cons)
    {
        $check = $cons->where('id_student',$request['student'])->where('id_num_plan',$request['modnum'])->get()->first();
        if ($check) {
            $cons->where('id',$check->id)->delete();
            return json_encode(['status'=>'true','message'=>trans("admin/modules/consulting.ok")]);
        }
        return json_encode(['status'=>'false','message'=>trans("admin/modules/consulting.error")]);
    }


    public function data()
    {

        $allowDiscepline = UserToDepartments::where('user_id',Auth::user()->id)->join('allowed_discipline','allowed_discipline.departments_id','=','user_to_departament.departments_id')->get()->first();

        $grades = GradesFiles::select(array(
//            'grades_files.id',
            'grades_files.EduYear',
            'grades_files.Semester',
            'cache_department.name as nameDep',
            'cache_speciality.name as nameSpec',
            'grades_files.NameDiscipline',
            'grades_files.NameModule',
            'type_exam.name as typeExamName',
            'grades_files.ModuleNum',
            'grades_files.ModuleVariantID',
            'grades_files.DisciplineVariantID',
            'grades_files.DepartmentId as DepartmentId',
            'grades_files.SpecialityId as SpecialityId',
        ))->distinct('ModuleVariantID')
            ->join('cache_department','cache_department.id_from','=','grades_files.DepartmentId')
            ->join('type_exam','grades_files.type_exam_id', '=', 'type_exam.id')
            ->join('cache_speciality','cache_speciality.id_from','=','grades_files.SpecialityId');
        if(isset($allowDiscepline->arrayAllowed)) {
            if (in_array(Auth::user()->role_id, [5]) && (count(json_decode($allowDiscepline->arrayAllowed)) > 0)) {
                $grades->whereIn('DisciplineVariantID', (array)json_decode($allowDiscepline->arrayAllowed));
            }
        }elseif(in_array(Auth::user()->role_id,[5])){
            $grades->whereIn('DisciplineVariantID',null);
        }
        $grades = $grades->get();

        foreach($grades as $key=>$grade) {
            $this->about_module = GradesFiles::where('ModuleVariantID',$grade->ModuleVariantID)->where('DepartmentId', $grade->DepartmentId)->get();
            $gradesEachStud = Grades::select('id_student')->distinct('id_student')->whereIn('grade_file_id', (array)$this->about_module->lists('id')->toArray())->get()->count();
            $consultGradeEachStud = ConsultingGrades::select('id_student')->distinct('id_student')->where('id_num_plan',$grade->ModuleVariantID)->where('department_id', $grade->DepartmentId)->get()->count();
            $consultGradeEachStud = ($consultGradeEachStud==0) ? 1 : $consultGradeEachStud;
            $gradesEachStud = ($gradesEachStud==0) ? 1 : $gradesEachStud;
            $grade->percent = number_format($consultGradeEachStud/$gradesEachStud*100,2).'%';
        }

        return Datatables::of($grades)
            ->edit_column('EduYear', '{{$EduYear}}/{{$EduYear+1}}')
            ->edit_column('NameModule', '{{$ModuleNum}}. {{$NameModule}}')
            ->add_column('percent','{{$percent}}')
            ->add_column('actions','@if(in_array(Auth::user()->role_id, [1,2,3,5,8])) <a href="{{ URL::to(\'teacher/\'.$DepartmentId.\'/\' . $ModuleVariantID . \'/edit\' )}}" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-pencil"></span>  {{ trans("admin/modules/consulting.grades") }}</a><a href="{{ URL::to(\'documents/\'.$DepartmentId.\'/\' . $ModuleVariantID . \'/false/getAllConsultingDocuments\' )}}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil"></span> Пустий шаблон </a>@endif @if (in_array(Auth::user()->role_id, [1,2,3,4,8])) <a href="{{ URL::to(\'documents/\'.$SpecialityId.\'/\'.$DepartmentId.\'/\' . $ModuleVariantID . \'/true/getAllDocumentsDeanery\' )}}" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil"></span>  Зведена відомість @endif')
            ->remove_column('ModuleVariantID')
            ->remove_column('DisciplineVariantID')
            ->remove_column('ModuleNum')
            ->remove_column('DepartmentId')
            ->remove_column('SpecialityId')
            ->make();
    }
}