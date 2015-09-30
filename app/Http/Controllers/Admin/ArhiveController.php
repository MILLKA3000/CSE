<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('role:Admin,Self-Admin');
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
        //
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

        $grades = GradesFiles::select(array(
            'grades_files.created_at',
            'grades_files.Semester',
            'grades_files.DepartmentId',
            'grades_files.SpecialityId',
            'grades_files.NameDiscipline',
            'type_exam.name as typeExamName',
            'users.name',
            'grades_files.id',
            'file_info.path',
            'grades_files.name as fileName',
            ))
            ->join('type_exam','grades_files.type_exam_id', '=', 'type_exam.id')
            ->join('file_info','grades_files.file_info_id', '=', 'file_info.id')
            ->join('users','file_info.user_id', '=', 'users.id')
            ->get();

        foreach($grades as $grade){
            $grade->DepartmentId = iconv("Windows-1251", "UTF-8",Departments::where('DEPARTMENTID',$grade->DepartmentId)->get()->first()->DEPARTMENT);
            $grade->SpecialityId = iconv("Windows-1251", "UTF-8",Speciality::where('SPECIALITYID',$grade->SpecialityId)->get()->first()->SPECIALITY);
        }

        return Datatables::of($grades)
//            ->edit_column('EduYear', '{{$EduYear}}/{{$EduYear+1}}')
//            ->remove_column('id')
            ->make();
    }


}
