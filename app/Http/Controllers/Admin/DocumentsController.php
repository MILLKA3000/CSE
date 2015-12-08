<?php

namespace App\Http\Controllers\Admin;

use App\ConsultingGrades;
use App\FileInfo;
use App\Grades;
use App\GradesFiles;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\CreateDocuments\ConsultingDocuments;
use App\Model\CreateDocuments\Documents;
use App\Model\Gaps\GetEmailEachStudent;
use App\XmlAgregate;
use File;
use App\Model\CreateDocuments\Statistics;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;



class DocumentsController extends Controller
{




    protected $student;

    public function __construct()
    {
        $this->middleware('role:Admin,Self-Admin,Inspektor,Teacher,Chief');
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
    public function getAllConsultingDocuments($depId,$numPlan,$check)
    {
        $doc = new ConsultingDocuments($depId,$numPlan,$check);
        return redirect($doc->formDocuments());
    }

    /**
     * @param $idFileGrade
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendEmails($idFileGrade)
    {
        $doc = new Documents($idFileGrade);
        foreach($doc['dataOfFile'] as $dataEachOfFile) {
            $dataGradesOfStudents = Grades::where('grade_file_id', $dataEachOfFile->id)->get();
            foreach ($dataGradesOfStudents as $student) {
                $consultinGrades  = ConsultingGrades::where('id_student',$student->id_student)->where('id_num_plan',$dataEachOfFile->ModuleVariantID)->get()->first();
                $this->student[$student->id_student][] = [
                    'discipline'=>$dataEachOfFile->NameDiscipline,
                    'name'=>$student->fio,
                    'module'=>$dataEachOfFile->NameModule,
                    'grade'=>$student->grade,
                    'examGrade'=>$student->exam_grade,
                    'consultGrade'=>(isset($consultinGrades))?$consultinGrades->grade_consulting:""
                ];
            }
        }

        foreach($this->student as $key=>$student) {
            Mail::send('emails.grades', ['data'=>$student], function ($message) use ($student,$key) {
                $message->sender(GetEmailEachStudent::where('student_id',$key)->get()->first()->user_name.'@tdmu.edu.ua', $name = 'You grades');
                $message->bcc(GetEmailEachStudent::where('student_id',$key)->get()->first()->user_name.'@tdmu.edu.ua', $name = 'You grades', 'Name')->subject('You grades');
            });
        }


        return redirect()->back();
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
        File::makeDirectory(public_path() .DIRECTORY_SEPARATOR . 'tmp', 0775, true, true);
        switch($name){
            case "general":
                File::put(public_path().DIRECTORY_SEPARATOR .'tmp'.DIRECTORY_SEPARATOR .'formGeneralStat.doc', $doc->formGeneralStat()['body']);
                return '\tmp\formGeneralStat.doc';
                break;
            case "bk":
                File::put(public_path().DIRECTORY_SEPARATOR .'tmp'.DIRECTORY_SEPARATOR .'formGeneralBKStat.doc', $doc->formGeneralBKStat()['body']);
                return '\tmp\formGeneralBKStat.doc';
                break;
            case "detailed":
                File::put(public_path().DIRECTORY_SEPARATOR .'tmp'.DIRECTORY_SEPARATOR .'formDetailedStat.doc', $doc->formDetailedStat()['body']);
                return '\tmp\formDetailedStat.doc';
                break;
        }
    }

    public function remove($id){
        FileInfo::destroy($id);
        return redirect('arhive');
    }


}
