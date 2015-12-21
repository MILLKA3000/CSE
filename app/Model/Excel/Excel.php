<?php

namespace App\Model\Excel;

use App\Helper\File;
use App\Model\Contingent\VariantDiscipline;
use App\TypeExam;
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

    public $id_grade_info;

    private $dataToSave;

    /**
     * for view message
     */
    private $messages=[[]];

    /**
     * @var array for examgrade
     */
    private $firstSheets = [];

    private $secondSheets = [];

    private $threeSheets = [];

    private $fourSheets = [];

    private $fiveSheets = [];

    private  $checkEachStudent = false;

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
        $this->firstSheetsOriginal = $this->OriginalData->get()[0];
        $this->data = $data;
    }


    /**
     * This function save ll data in tables
     */
    public function SaveData(){
        if($this->validateTables()){
            $this->id_file = $this->setFileInfo();
            $module = 0;
            foreach($this->OriginalData->get()[3] as $fourSheets) {
                $this->id_grade_info = $this->setGradeFile($fourSheets);
                $this->setGrades($this->dataToSave[$module]);
                $module++;
            }
            $this->messages['success'][] = 'Save data complicated!';
        }
        return $this->messages;
    }

    /**
     * This func has all functions of validate
     */
    private function validateTables(){
        $this->concatAnotherPage();
        $checkError = true;
        $this->rebuildDataInFirstSheets(); // for find student code in index array
        $this->rebuildDataInSecondSheets();
        $this->rebuildDataInThreeSheets();
        if(isset($this->OriginalData->get()[4])){
            $this->rebuildDataInFiveSheets();
            $this->checkEachStudent = true;
        }

        $this->validationCodeAndGrades();
        if(isset($this->messages['error'])){
            $checkError = false;
        }
        return $checkError;
    }

    private function concatAnotherPage(){
        foreach($this->firstSheetsOriginal as $key=>$firstSheets) { // iteration in first sheet
            $data = $this->convertCellFromFirstSheet($firstSheets);
            if($data['page'] == 2){
                if($this->findFirstPage($data))unset( $this->firstSheetsOriginal[$key]);
            }
        }
    }

    private function findFirstPage($dataSecondPage){
        foreach($this->firstSheetsOriginal as $key => $firstSheets) { // iteration in first sheet
            $data = $this->convertCellFromFirstSheet($firstSheets);
            if($data['code'] == $dataSecondPage['code']){



                for($i=0;$i < count($dataSecondPage['exam_grade']);$i++){
                    $firstSheets["concat_from_2_page_".$i] = $dataSecondPage['exam_grade'][$i];
                }

//                if(($firstSheets['storinka']!=1)){
//                    $firstSheets['storinka']=1;
//                    $this->firstSheetsOriginal[$key] = $firstSheets;
//                }

                $this->firstSheetsOriginal[$key] = $firstSheets;

                return true;
            }
        }
        return false;
    }

    /**
     * each code of student from second sheet find code of student in first sheet and add together
     */
    private function validationCodeAndGrades(){
        $grades = [];
        $module = 0;

        foreach($this->OriginalData->get()[3] as $fourSheets) {

            foreach($this->OriginalData->get()[1] as $secondSheets) { // iteration in second sheet
                $grades['code'] = (int)$secondSheets['code'];
                $grades['id_student'] = (string)$secondSheets['id'];
                $grades['fio'] = $secondSheets['fio'];
                $grades['group'] = (int)$secondSheets['group'];
                $grades['grade'] = (int)$this->threeSheets[(string)$secondSheets['id']][$module];
                if($this->checkEachStudent===false){$this->fiveSheets[$grades['id_student']][$module]=true;}
                if($this->fiveSheets[$grades['id_student']][$module]) {
                    if (($secondSheets['code'] != 999) && ($secondSheets['code'] != 0)) {
                        if (isset($this->firstSheets[$secondSheets['code']])) {
                            $grades['exam_grade'] = (int)$this->firstSheets[$secondSheets['code']][$module];
                            $this->dataToSave[$module][] = $grades;
                        } else {
                            $this->messages['error'][] = 'Don\'t find code in First Sheets : ' . $secondSheets['code'] . ' for module ' . ((int)$module + 1);
                        };
                    } else {
                        $grades['exam_grade'] = 0;
                        $this->dataToSave[$module][] = $grades;
                    }
                }
            }
            $module++;
        }

    }

    /**
     * Save to information table
     *
     * @return static
     */
    private function setFileInfo(){

        $data['path']=File::_getTimestampPath($this->data['path']);
        $data['user_id']=Auth::user()->id;

        return FileInfo::create($data);
    }


    private function setGrades($dataGrades){
        foreach($dataGrades as $data){
            $data['grade_file_id'] = $this->id_grade_info->id;
            Grades::create($data);
        }
        return true;
    }


    /**
     * @return static
     */
    private function setGradeFile($fourSheets){
        $data['name']=$this->data['urlOriginalName'];
        $data['qty_questions']=$this->data['qtyQuestions'];
        $data['file_info_id']=$this->id_file->id;
        $data['EduYear']=(string)$fourSheets['eduyear'];
        $data['Semester']=(string)$fourSheets['semester'];
        $data['DepartmentId']=(string)$fourSheets['departmentid'];
        $data['SpecialityId']=(string)$fourSheets['specialityid'];
        $data['DisciplineVariantID']=(string)$fourSheets['disciplinevariantid'];
        $data['ModuleVariantID']=(string)$fourSheets['modulevariantid'];
        $data['ModuleNum']=(string)$fourSheets['modulenum'];
        $data['NameDiscipline']=(string)$fourSheets['namediscipline'];
        $data['NameModule']=(string)$fourSheets['namemodule'];
        $data['type_exam_id']=TypeExam::where('name',VariantDiscipline::getFormReport($data['DisciplineVariantID']))->get()->first()->id;
        return GradesFiles::create($data);
    }

    /**
     * @param $arr
     * @return mixed
     */
    private function convertCellFromFirstSheet($arr){
        $tmp = array_values(array_values((array)$arr)[1]);
        $data['page'] = $tmp[0];
        $data['code'] = $tmp[2];
        for($i=3;$i<count($tmp);$i++){
            $data['exam_grade'][] = $tmp[$i];
        }
        return $data;
    }

    /**
     * @param $arr
     * @return mixed
     */
    private function convertCellFromThreeSheet($arr){
        $tmp = array_values(array_values((array)$arr)[1]);
        $data['studentId'] = $tmp[0];
        for($i=2;$i<count($tmp);$i++){
            $data['grade'][] = $tmp[$i];
        }
        return $data;
    }

    /**
     * @param $arr
     * @return mixed
     */
    private function convertCellFromFiveSheet($arr){
        $tmp = array_values(array_values((array)$arr)[1]);
        $data['studentId'] = $tmp[0];
        for($i=2;$i<count($tmp);$i++){
            if (isset($tmp[$i])){
                $data['check'][] = true;
            }else{
                $data['check'][] = false;
            }

        }
        return $data;
    }

    private function rebuildDataInFirstSheets(){
        foreach($this->firstSheetsOriginal as $key=>$secondSheets) { // iteration in first sheet
            $afterGet = $this->convertCellFromFirstSheet($secondSheets);
            if (isset($this->firstSheets[$afterGet['code']])){
                $this->messages['error'][] = 'In first sheet duplicate code: '.$afterGet['code'];
            }
            $this->firstSheets[$afterGet['code']] = $afterGet['exam_grade'];
        }
    }

    private function rebuildDataInSecondSheets(){
        foreach($this->OriginalData->get()[1] as $key=>$secondSheets) { // iteration in second sheet
            $afterGet = $this->convertCellFromFirstSheet($secondSheets);
            if (isset($this->secondSheets[$afterGet['code']]) && $afterGet['code']!=999 && $afterGet['code']!=0){
                $this->messages['error'][] = 'In second sheet duplicate code: '.$afterGet['code'];
            }
            $this->secondSheets[$afterGet['code']] = true;
        }
    }

    private function rebuildDataInThreeSheets(){
        foreach($this->OriginalData->get()[2] as $key=>$secondSheets) { // iteration in three sheet
            $afterGet = $this->convertCellFromThreeSheet($secondSheets);
            $this->threeSheets[(string)$afterGet['studentId']] = $afterGet['grade'];
        }
    }

    private function rebuildDataInFiveSheets(){
        foreach($this->OriginalData->get()[4] as $key=>$secondSheets) { // iteration in five sheet
            $afterGet = $this->convertCellFromFiveSheet($secondSheets);
            $this->fiveSheets[(string)$afterGet['studentId']] = $afterGet['check'];
        }
    }

}
