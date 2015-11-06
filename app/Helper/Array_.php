<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use App\Model\Contingent\Students as ContStudent;

class Array_ extends Model
{

    /**
     * Get data from XML and form array for XLS
     *
     * @param $obj
     * @return array
     */
    static public function formXLSConnectTable($obj)
    {
        $data = [];
        foreach ($obj->getContent() as $d) {
            foreach ($d->students->student as $student) {
                $data[(string)$student->id] = [(string)$student->id, (string)$student->fio, '', (string)$d->groupnum];
            }
        }
        return $data;
    }


    /**
     * Get data from XML and form array for XLS
     *
     * @param $obj
     * @return array
     */
    static public function formXLSArrayGrade($datas)
    {
        $data = [];
        $moduleNum = 0;
        foreach($datas as $d){
            foreach ($d['data']->getContent() as $module) {
                foreach ($module->students->student as $student) {
                    if ($moduleNum==0) {
                        $data[(string)$student->id] = [(string)$student->id, (string)$student->fio, (string)$student->credits_cur];
                    }else{
                        foreach($module->students->student as $findRepeate){
                            if($findRepeate->id == $student->id){
                                $data[(string)$student->id][$moduleNum] = (string)$findRepeate->credits_cur;
                            }
                        }
                    }
                }
            }
            $moduleNum++;
        }
        return $data;
    }


    /**
     * Get information of module
     *
     * @param $obj
     * @return array
     *
     */
    static public function getInfoForXLS($obj)
    {
        $data = [];
        $specialityId = ContStudent::getStudentSpeciality($obj[0]['data']->getContent()->testlist->students->student->id);
        foreach($obj as $d) {
            $content = $d['data']->getContent();
            $data[] = [
                (string)$content->testlist->eduyear,
                (string)$content->testlist->semester,
                (string)$content->testlist->departmentid,
                $specialityId,
                (string)$content->testlist->disciplinevariantid,
                (string)$content->testlist->modulevariantid,
                (string)$content->testlist->modulenum,
                (string)$content->testlist->discipline,
                (string)$content->testlist->moduletheme,
            ];
        }
        return $data;
    }





    /**
     * not used (tested function)
     *
     * @param $obj
     * @return array
     */
    static public function formXLSArrayToTableDecode($obj)
    {
        $data = [];
        foreach ($obj as $d) {
            foreach ($d->students->student as $student) {
                $data[] = [(string)$student->id, (string)$student->fio];
            }
        }
        return $data;
    }


}
