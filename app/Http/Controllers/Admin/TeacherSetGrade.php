<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ConsultingGrades;
use App\User;
use App\CacheDepartment;
use App\CacheSpeciality;
use App\GradesFiles;
use Datatables;

class TeacherSetGrade extends Controller
{

    function __construct()
    {
        $this->middleware('role:Admin,Teacher');
        view()->share('type', 'teacher');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.consulting.index');
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

    public function data()
    {

        $grades = GradesFiles::select(array(
            'grades_files.EduYear',
            'grades_files.Semester',
            'grades_files.DepartmentId',
            'grades_files.SpecialityId',
            'grades_files.NameDiscipline',
            'grades_files.NameModule',
            'grades_files.ModuleNum',
        ))->get();

        foreach($grades as $grade){
            $grade->DepartmentId = CacheDepartment::getDepartment($grade->DepartmentId)->name;
            $grade->SpecialityId = CacheSpeciality::getSpeciality($grade->SpecialityId)->name;
        }

        return Datatables::of($grades)
            ->edit_column('EduYear', '{{$EduYear}}/{{$EduYear+1}}')
            ->edit_column('NameModule', '{{$ModuleNum}}. {{$NameModule}}')
            ->remove_column('id')
            ->make();
    }
}
