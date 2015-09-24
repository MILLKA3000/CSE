<?php

namespace App\Model\CreateDocuments;

use App\Grades;
use App\GradesFiles;
use App\Model\Contingent\Speciality;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $dataGradesOfStudents;

    protected $dataGradesOfStudentsGroups;

    protected $dataOfFile;

    protected $speciality;

    private $idFileGrade;

    public function __construct($idFileGrade)
    {
        $this->idFileGrade = $idFileGrade;
    }

    public function formDocument(){
        $this->dataGradesOfStudents = Grades::where('grade_file_id',$this->idFileGrade)->get();
        $this->dataGradesOfStudentsGroups = Grades::select('group')->where('grade_file_id',$this->idFileGrade)->distinct()->get();
        $this->dataOfFile = GradesFiles::find($this->idFileGrade);
        $this->speciality = Speciality::where('SPECIALITYID',$this->dataOfFile->SpecialityId)->get()->first()->SPECIALITY;
        $this->formHtml();
    }

    public function formHtml(){
        dd($this->dataGradesOfStudentsGroups);
//        foreach($dataGradesOfStudents as )
    }

}
