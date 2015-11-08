<?php

namespace App\Http\Controllers\Admin;

use App\AllowedDiscipline;
use App\Departments;
use App\GradesFiles;
use App\Logs;
use App\User;
use App\UserToDepartments;
use Datatables;
use App\Http\Requests\Admin\DepartmentRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DepartmentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Inspektor,Dekanat');
        view()->share('type', 'department');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $discipline = GradesFiles::select('DisciplineVariantID','NameDiscipline')->distinct()->get();
        $discipline_allowed = AllowedDiscipline::where('id',0)->get();
        return view('admin.department.create_edit', compact('users','discipline','discipline_allowed'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        $department = new Departments(['name'=>$request->name,'note'=>$request->note,'active'=>$request->active]);
        unset($department->users);
        $department->save();

        $allowedDiscipline = new AllowedDiscipline(['departments_id'=>$department->id,'arrayAllowed'=>json_encode($request->discipline_allowed)]);
        $allowedDiscipline->save();

        $userToDepartment = new UserToDepartments();
        $userToDepartment->departments_id = $department->id;
        $userToDepartment->user_id = $request->users;
        $userToDepartment->save();

        Logs::_create('Department create '.$department->name);
        return redirect('/department');
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
     * @param Departments $department
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function edit(Departments $department)
    {
        $users = User::all();
        $discipline = GradesFiles::select('grades_files.DisciplineVariantID',DB::raw('CONCAT("[", type_exam.name,"] ",NameDiscipline) AS NameDiscipline'))->join('type_exam','type_exam.id','=','grades_files.type_exam_id')->distinct()->get();
        $discipline_allowed = clone $discipline;
        $array_discipline_allowed = AllowedDiscipline::where('departments_id',$department->id)->get()->first();
        if(isset($array_discipline_allowed->id)){
            foreach ($discipline as $key => $dis) {
                if (in_array($dis->DisciplineVariantID, array_flatten((array)json_decode($array_discipline_allowed->arrayAllowed)))) {
                    unset($discipline[$key]);
                }else{
                    unset($discipline_allowed[$key]);
                }
            }
        }else{
            $discipline_allowed = AllowedDiscipline::where('id',0)->get();
        }
        return view('admin.department.create_edit', compact('department','users','discipline','discipline_allowed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DepartmentRequest $request
     * @param Departments $department
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(DepartmentRequest $request, Departments $department)
    {
        $department->update([
            'name'=>$request->name,
            'note'=>$request->note,
            'active'=>$request->active,
        ]);

        AllowedDiscipline::where('departments_id',$department->id)->delete();
        $allowedDiscipline = new AllowedDiscipline(['departments_id'=>$department->id,'arrayAllowed'=>json_encode($request->discipline_allowed)]);
        $allowedDiscipline->save();

        $userToDepartment = UserToDepartments::find($department->id);
        $userToDepartment->user_id = $request->users;
        $userToDepartment->update();

        Logs::_create('User update department '.$department->name);
        return redirect('/department');
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param Departments $department
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Departments $department)
    {
        Logs::_create('User deleted department '.$department->name);
        $department->delete();
        return redirect()->back();
    }


    /**
     * Show a list of all the departments posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $departments = Departments::all(array('departments.id', 'departments.name', 'departments.active'));

        foreach($departments as $department) {
            $department->user = User::find($department->getUser()->user_id)->name;
        }

        return Datatables::of($departments)
            ->edit_column('active', '@if ($active=="1") Active @else Deactive @endif')
            ->add_column('user', '{{$user}}')
            ->add_column('actions', '<a href="{{{ URL::to(\'department/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm " ><span class="glyphicon glyphicon-pencil"></span>  {{ trans("admin/modal.edit") }}</a>
                    <a href="{{{ URL::to(\'department/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.delete") }}</a>')
            ->remove_column('id')
            ->make();
    }
}
