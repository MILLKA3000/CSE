<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Recoding;
use App\Model\Contingent\Departments;
use App\Model\Contingent\Speciality;
use App\Model\Contingent\Students;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Datatables;

class SubjectContingentController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Admin');
        view()->share('type', 'subject');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $departments = Departments::getAllDepartment();

        $speciality = Speciality::getAllSpeciality();

        return view('admin.subject.index',compact('departments','speciality'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show a list
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $subjects = Speciality::all(['SPECIALITY','SPECIALITYID']);

        foreach($subjects as $subject){
            $subject->SPECIALITY = iconv("windows-1251","utf-8",$subject->SPECIALITY);
        }


        return Datatables::of($subjects)
            ->make();
    }

}
