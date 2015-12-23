<?php

namespace App\Helper;

use App\ConsultingGrades;
use App\Grades;
use App\GradesFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Storage;
use Orchestra\Parser\Xml\Document;
use Orchestra\Parser\Xml\Reader;
use File as FileOr;

class XML extends Model
{
    protected $app;
    protected $document;
    protected $stub;
    protected $xml;

    public function __construct()
    {
        $this->app      = new Container();
        $this->document = new Document($this->app);
        $this->stub     = new Reader($this->document);
    }

    public function parseFromUrl($url){
        return $this->xml = $this->stub->load($url);
    }

    public function countStudents(){
        $students_q = 0;
        foreach($this->xml->getContent() as $d){
            foreach($d->students->student as $student){
                $students_q++;
            }
        }

        return $students_q;
    }


    static public function putGradesInXml($obj,$name)
    {
        foreach ($obj->getContent() as $d) {
            $module = GradesFiles::where('ModuleVariantID',$d->modulevariantid)->orderBy('created_at', 'desc')->get()->first();
            foreach ($d->students->student as $student) {;
                $examGrade = Grades::where('id_student',$student->id)->where('grade_file_id',142)->orderBy('created_at', 'desc')->get()->first();
                $consultingGrades = ConsultingGrades::where('id_student',$student->id)->where('id_num_plan',$module->ModuleVariantID)->get()->first();
		dd($examGrade);
                if(isset($examGrade)){
                    $student->credits_test = $examGrade->exam_grade+(isset($consultingGrades->grade_consulting)?$consultingGrades->grade_consulting:0);
                }
            }
        }
        return $obj->getContent()->asXml(public_path() . DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."XML".DIRECTORY_SEPARATOR.$name);
    }

}
