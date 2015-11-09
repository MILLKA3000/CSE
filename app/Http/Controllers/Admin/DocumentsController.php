<?php

namespace App\Http\Controllers\Admin;

use App\FileInfo;
use App\Grades;
use App\GradesFiles;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\CreateDocuments\ConsultingDocuments;
use App\Model\CreateDocuments\Documents;
use App\XmlAgregate;
use File;
use App\Model\CreateDocuments\Statistics;
use Illuminate\Support\Facades\Response;


class DocumentsController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Inspektor,Teacher');
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
     * @param $numPlan
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getAllConsultingDocuments($numPlan)
    {
        $doc = new ConsultingDocuments($numPlan);
        return redirect($doc->formDocuments());
    }

    /**
     * @param $idFileGrade
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendEmails($idFileGrade)
    {

    }

    /**
     *
     */
    public function getAllStatistics($idFileGrade)
    {
        $doc = new Statistics($idFileGrade);
        $data['general'] = $doc->formGeneralStat();
        $data['bk'] = $doc->formGeneralBKStat();
        $data['detailed'] = $doc->formDetailedStat();

        return view('admin.documents.allStatistics',compact('data','idFileGrade'));
    }


    /**
     * @param $name
     */
    public function downloadStatistics($name,$idFileGrade){
        $doc = new Statistics($idFileGrade);
        File::makeDirectory(public_path() . '\tmp', 0775, true, true);
        switch($name){
            case "general":
                File::put(public_path().'\tmp\formGeneralStat.doc', $doc->formGeneralStat()['body']);
                return '\tmp\formGeneralStat.doc';
                break;
            case "bk":
                File::put(public_path().'\tmp\formGeneralBKStat.doc', $doc->formGeneralBKStat()['body']);
                return '\tmp\formGeneralBKStat.doc';
                break;
            case "detailed":
                File::put(public_path().'\tmp\formDetailedStat.doc', $doc->formDetailedStat()['body']);
                return '\tmp\formDetailedStat.doc';
                break;
        }
    }

    public function remove($id){
        FileInfo::destroy($id);
        return redirect('arhive');
    }


}
