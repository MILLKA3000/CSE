<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\CreateDocuments\Documents;
use App\Helper\File;
use App\Model\CreateDocuments\Statistics;
use Illuminate\Support\Facades\Response;


class DocumentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Inspektor');
        view()->share('type', 'documents');
    }

    /**
     * @param $idFileGrade
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getAllDocuments($idFileGrade)
    {
        $doc = new Documents($idFileGrade);
        return redirect($doc->formDocuments());
    }



    /**
     *
     */
    public function getAllStatistics($idFileGrade)
    {
        $doc = new Statistics($idFileGrade);
        $doc->formDocuments();
    }


}
