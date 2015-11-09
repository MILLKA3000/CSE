<?php

namespace App\Http\Controllers\Admin;

use App\CacheDepartment;
use App\CacheSpeciality;
use App\Grades;
use App\GradesFiles;
use App\FileInfo;
use App\Model\Contingent\Departments;
use App\Model\Contingent\Speciality;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Datatables;

class ArhiveController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Inspektor,Dekanat');
        view()->share('type', 'arhive');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.archive.docs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modules = GradesFiles::where('file_info_id',$id)->get();
        foreach($modules as $module){
            $module->DepartmentId = CacheDepartment::getDepartment($module->DepartmentId)->name;
            $module->SpecialityId = CacheSpeciality::getSpeciality($module->SpecialityId)->name;
            $module->quantityStudents = Grades::where('grade_file_id',$module->id)->get()->count();
            $module->quantityGroups = Grades::select('group')->where('grade_file_id',$module->id)->distinct()->get()->count();
        }
        $modules->path = FileInfo::where('id',$modules[0]->file_info_id)->get()->first()->path;
        $modules->name = $modules[0]->name;
        $modules->file_info_id = $modules[0]->file_info_id;
        return view('admin.archive.docs.view',compact('modules'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show a list of archive.
     *
     * @return Datatables JSON
     */
    public function data()
    {

        $modules = FileInfo::select(array(
            'xls_file_info.created_at',
            'grades_files.Semester',
            'grades_files.DepartmentId',
            'grades_files.SpecialityId',
            'users.id as disciplines',
            'type_exam.name as typeExamName',
            'users.name as userName',
            'xls_file_info.id',
            'xls_file_info.path',
            'grades_files.name',
        ))
            ->join('grades_files','grades_files.file_info_id', '=', 'xls_file_info.id')
            ->join('users', 'users.id', '=', 'xls_file_info.user_id')
            ->join('type_exam','grades_files.type_exam_id', '=', 'type_exam.id')
            ->distinct()
            ->get();

        foreach($modules as $module){
            $module['disciplines'] =  GradesFiles::select(array(
                'grades_files.NameDiscipline',
                'grades_files.NameModule',
            ))->where('file_info_id',$module['id'])
                ->get();
        }

        foreach($modules as $module){
            $module->DepartmentId = CacheDepartment::getDepartment($module->DepartmentId)->name;
            $module->SpecialityId = CacheSpeciality::getSpeciality($module->SpecialityId)->name;
        }

        return Datatables::of($modules)
            ->edit_column('created_at', '<?php echo date("Y-m-d", strtotime($created_at)) ?>')
            ->edit_column('disciplines', '<?php $i=0; ?>@foreach($disciplines as $discipline) <span style="border-bottom: 1px solid #64DD8A;width:100%;display: block;">{{++$i}}. {{$discipline["NameDiscipline"]}}({{$discipline["NameModule"]}}), <br></span> @endforeach')
            ->add_column('actions', '<a href="{{{ URL::to(\'arhive/\' . $id) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-pencil"></span> {{ trans("admin/modal.detail") }}</a>')
            ->remove_column('id')
            ->remove_column('path')
            ->remove_column('name')
            ->make();
    }


}
