<?php

namespace App\Model\Excel;

use Illuminate\Database\Eloquent\Model;
use App\FileInfo;
use App\Grades;
use App\GradesFiles;
use Logs;
use Auth;


class Excel extends Model
{

    private $OriginalData;

    private $data;

    public function __construct($OriginalData,$data)
    {
       $this->OriginalData = $OriginalData;
       $this->data = $data;
    }

    public function SaveData(){

        $id_file = $this->setFileInfo();
        $this->setGradeFile($id_file->id);
    }

    private function setFileInfo(){

        $data['path']=$this->data['path'];
        $data['user_id']=Auth::user()->id;

        return FileInfo::create($data);
    }

    private function setGradeFile($id_file){
        $data['name']=$this->data['urlOriginalName'];
        $data['file_info_id']=$id_file;
        $data['EduYear']=(string)$this->OriginalData->get()[2][0]['eduyear'];
        $data['Semester']=(string)$this->OriginalData->get()[2][0]['semester'];
        $data['DepartmentId']=(string)$this->OriginalData->get()[2][0]['departmentid'];
        $data['SpecialityId']=(string)$this->OriginalData->get()[2][0]['specialityid'];
        $data['DisciplineVariantID']=(string)$this->OriginalData->get()[2][0]['disciplinevariantid'];
        $data['ModuleVariantID']=(string)$this->OriginalData->get()[2][0]['modulevariantid'];
        $data['ModuleNum']=(string)$this->OriginalData->get()[2][0]['modulenum'];
        $data['NameDiscipline']=(string)$this->OriginalData->get()[2][0]['namediscipline'];
        $data['NameModule']=(string)$this->OriginalData->get()[2][0]['namemodule'];
        $data['type_exam_id']=$this->data['type_exam'];

        return GradesFiles::create($data);
    }


}
