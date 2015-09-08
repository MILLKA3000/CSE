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
        $this->value_student=[];
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


        $data = $stub->load('xml/1.xml');
        $this->value_student = $data;

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
//        return Datatables::of($this->value_student->getContent()->students->student->fio->toArray())
//            ->make();
    }

}
