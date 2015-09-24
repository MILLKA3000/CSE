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
    /**
     * Obj with data from xls
     */
    private $OriginalData;

    /**
     * Obj with data from form (path, name_file, etc)
     */
    private $data;

    public $id_file;

    private $dataToSave;

    /**
     * for view message
     */
    private $messages=[[]];

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
        if($this->validateTables()){
            $this->id_file = $this->setFileInfo();
            $this->setGradeFile($this->id_file->id);
            $this->setGrades($this->id_file->id);
            $this->messages['success'][] = 'Save data complicated!';
        }
        return $this->messages;
    }

    /**
     * This func has all functions of validate
     */
    private function validateTables(){
        $checkError = true;
        $this->validationCodeAndGrades();
        $this->validationRepeatCode();
        if(isset($this->messages['error'])){
            $checkError = false;
        }
        return $checkError;
    }

    /**
     * each code of student from second sheet find code of student in first sheet and add together
     */
    private function validationCodeAndGrades(){
        $grades = [];
        foreach($this->OriginalData->get()[1] as $secondSheets) { // iteration in second sheet
            if ($secondSheets['kode']!='n' or $secondSheets['kode']!='0') {
                $dataOfGrades = $this->findGradesInFirstSheet($this->OriginalData->get()[0], $secondSheets['kode']);
                if ($dataOfGrades) {
                    $grades['code'] = (int)$dataOfGrades['code'];
                    $grades['id_student'] = $secondSheets['id'];
                    $grades['fio'] = $secondSheets['fio'];
                    $grades['group'] = (int)$secondSheets['group'];
                    $grades['exam_grade'] = (int)$dataOfGrades['exam_grade'];
                    $grades['grade'] = (int)$secondSheets['modulegrade'];
                    $this->dataToSave[] = $grades;
                } else {
                    $this->messages['error'][] = 'Don\'t find code in First Sheets : ' . $secondSheets['kode'];
                }
            }

        }
    }

    /**
     * find duplicate code of student
     */
    private function validationRepeatCode(){
        $countArrGrades = count($this->dataToSave);
        for ($i=0;$i<$countArrGrades;$i++){
            for ($j=$i+1;$j<$countArrGrades;$j++){
                if ($this->dataToSave[$i]['code'] == $this->dataToSave[$j]['code']){
                    $this->messages['error'][] = 'Find duplicate code : '. $this->dataToSave[$i]['code']. ', students: '.$this->dataToSave[$i]['fio'].', '.$this->dataToSave[$j]['fio'];
                }
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


    private function setGrades($id_file){
        foreach($this->dataToSave as $data){
            $data['grade_file_id'] = $id_file;
            Grades::create($data);
        }
        return true;
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

    /**
     * @param $sheet
     * @param $kode
     * @return bool
     */
    private function findGradesInFirstSheet($sheet,$kode){
        foreach($sheet as $firstSheets){
            $kodeInFirstSheet = $this->convertCellFromFirstSheet($firstSheets);
            if($kodeInFirstSheet['code']==$kode){
                return $kodeInFirstSheet;
            }
        }
        return false;
    }

    /**
     * @param $arr
     * @return mixed
     */
    private function convertCellFromFirstSheet($arr){
        $tmp = array_values(array_values((array)$arr)[1]);
        $data['code'] = $tmp[2];
        $data['exam_grade'] = $tmp[3];
        return $data;
    }

}
