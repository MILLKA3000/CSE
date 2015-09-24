<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\CreateDocuments\Documents;

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
        $doc->formDocument();
    }


    /**
     *
     */
    public function getAllStatistics()
    {

    }


}
