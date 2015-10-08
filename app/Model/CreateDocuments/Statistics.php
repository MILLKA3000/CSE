<?php

namespace App\Model\CreateDocuments;

use App\CacheDepartment;
use App\CacheSpeciality;
use App\GradesFiles;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Model;
use App\Grades;

class Statistics extends Model
{

    protected $DOC_PATH; // for puts files

    protected $studentsOfGroup; // Students of group

    protected $dataGradesOfStudentsGroups; // for find all groups

    protected $dataOfFile; // each module

    protected $dataEachOfFile; // select module

    protected $speciality; // get from cache speciality

    protected $department; // get from cache department

    protected $idFileGrade = 0;

    protected $shablon;

    private $numModule = 1;

    public function __construct($idFileGrade)
    {
        $this->dataOfFile = GradesFiles::where('file_info_id',$idFileGrade)->get();

        /**
         * get data from bd about module (generals data for each docs)
         */
        $this->speciality = CacheSpeciality::getSpeciality($this->dataOfFile[0]->SpecialityId)->name;
        $this->department = CacheDepartment::getDepartment($this->dataOfFile[0]->DepartmentId)->name;

    }


    /**
     * Public func for prepare get data
     */
    public function formData(){

        foreach($this->dataOfFile as $this->dataEachOfFile) {
            $this->studentsOfGroup = [];
            $this->dataGradesOfStudentsGroups = Grades::where('grade_file_id', $this->dataEachOfFile->id)->get();
            foreach ($this->dataGradesOfStudentsGroups as $student) {
                $this->studentsOfGroup[$this->numModule][$student['group']][] = $student;
            }
            $this->numModule++;
        }

//        $stat['general'] = $this->formGeneralStat();


    }

    public function formGeneralStat()
    {
        $this->shablon = '';
        $this->formData();

        $this->shablon .= $this->formHeader('General statistic');

        $this->shablon .=$this->formFooter();

        return $this->shablon;
    }

    public function formGeneralBKStat()
    {
        $this->shablon = '';
        $this->formData();

        $this->shablon .= $this->formHeader('General statistic of contract and butjet students');

        $this->shablon .=$this->formFooter();

        return $this->shablon;
    }

    public function formHeader($textHead)
    {
        $text ='';

        $text.='<center>'.$textHead.'</center>';
        $text.='<table border="1">';
        return $text;
    }

    public function formFooter()
    {
        $text ='';

        $text.='</table>';

        return $text;
    }

}
