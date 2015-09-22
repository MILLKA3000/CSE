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

    private $grades;

    private $errorMessages;

    /**
     * Construct for init data
     * Original data this is data from xls
     * data this is array which has inside url,name xml,etc.
     *
     * @param array $OriginalData
     * @param $data
     */
    public function __construct($OriginalData,$data)
    {
       $this->OriginalData = $OriginalData;
       $this->data = $data;
    }


    /**
     * This function save ll data in tables
     */
    public function SaveData(){
        $this->validateTables($this->OriginalData);
       // $id_file = $this->setFileInfo();
       // $this->setGradeFile($id_file->id);
//        $this->setGrades($this->OriginalData);
    }


    private function validateTables($data){
        if($this->validationRepeatCode($data)){
            $this->validationCodeAndGrades($data);
        };

    }

    private function validationCodeAndGrades($data){
        $dataToSave = [];
        foreach($data->get()[1] as $secondSheets) { // iteration in second sheet
            $dataOfGrades = $this->findGradesInFirstSheet($data->get()[0],$secondSheets['kode']);
            if($dataOfGrades){
                $this->grades['code'] = (int)$dataOfGrades['code'];
                $this->grades['id_student'] = $secondSheets['id'];
                $this->grades['fio'] = $secondSheets['fio'];
                $this->grades['group'] = (int)$secondSheets['group'];
                $this->grades['exam_grade'] = (int)$dataOfGrades['exam_grade'];
                $this->grades['grade'] = (int)$secondSheets['modulegrade'];
                $dataToSave[] = $this->grades;
            }else{
                $this->errorMessages[] = 'Don\'t code in First Sheets : '. $secondSheets['kode'];
            }

        }
    }

    /**
     * Save to information table
     *
     * @return static
     */
    private function setFileInfo(){

        $data['path']=$this->data['path'];
        $data['user_id']=Auth::user()->id;

        return FileInfo::create($data);
    }


    /**
     * @param $id_file
     * @return static
     */
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

    private function findGradesInFirstSheet($sheet,$kode){
        foreach($sheet as $firstSheets){
            $kodeInFirstSheet = $this->convertCellFromFirstSheet($firstSheets);
            if($kodeInFirstSheet['code']==$kode){
                return $kodeInFirstSheet;
            }
        }
        return false;
    }


    private function convertCellFromFirstSheet($arr){
        $tmp = array_values(array_values((array)$arr)[1]);
        $data['code'] = $tmp[2];
        $data['exam_grade'] = $tmp[3];
        return $data;
    }

}
