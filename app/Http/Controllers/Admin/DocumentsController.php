<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\CreateDocuments\Documents;
use App\Helper\File;
use Illuminate\Support\Facades\Response;


class DocumentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Admin');
        view()->share('type', 'documents');
    }

    public function getAllDocuments($idFileGrade)
    {
        $doc = new Documents($idFileGrade);
        return Response::download($doc->formDocument(),'Documents.zip',array('content-type' => 'application/zip'));
    }


    /**
     *
     */
    public function getAllStatistics()
    {

    }


}
