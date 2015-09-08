<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Input;
use App\Language;
use App\Http\Requests\Admin\LanguageRequest;
use App\Http\Requests\Admin\DeleteRequest;
use App\Http\Requests\Admin\ReorderRequest;
use Illuminate\Support\Facades\Auth;
use Datatables;
use App\Logs;

class LanguageController extends AdminController {

    public function __construct()
    {
        $this->middleware('role:Admin');
        view()->share('type', 'language');
    }
    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        // Show the page
        return view('admin.language.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
       // Show the page
        return view('admin/language/create_edit');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(LanguageRequest $request)
	{
        $language = new Language($request->all());
        $language -> user_id = Auth::id();
        $language -> save();
        Logs::_create('User create language '.$language->name);
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Language $language)
	{
        return view('admin/language/create_edit',compact('language'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(LanguageRequest $request, Language $language)
	{
        $language -> user_id_edited = Auth::id();
        $language -> update($request->all());
        Logs::_create('User update language '.$language->name);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */

    public function delete(Language $language)
    {
        Logs::_create('User deleted language '.$language->name);
        $language->delete();
        return redirect()->back();
    }


    /**
     * Show a list of all the languages posts formatted for Datatables.
     *
     * @return Datatables JSON
     */
    public function data()
    {
        $language = Language::whereNull('languages.deleted_at')
            ->orderBy('languages.position', 'ASC')
            ->select(array('languages.id', 'languages.name', 'languages.lang_code as lang_code'));
        return Datatables::of($language)
            ->add_column('actions', '<a href="{{{ URL::to(\'language/\' . $id . \'/edit\' ) }}}" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-pencil"></span> {{ trans("admin/modal.edit") }}</a>
                    <a href="{{{ URL::to(\'language/\' . $id . \'/delete\' ) }}}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash"></span> {{ trans("admin/modal.delete") }}</a>
                    <input type="hidden" name="row" value="{{$id}}" id="row">')
            ->remove_column('id')

            ->make();
    }

}
