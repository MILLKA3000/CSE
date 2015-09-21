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
    static public function formXLSArrayGrade($obj)
    {
        $data = [];
        foreach ($obj as $d) {
            foreach ($d->students->student as $student) {
                $data[] = [(string)$student->id, (string)$student->fio, '', (string)$d->groupnum, (string)$student->credits_cur];
            }
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
        $data[] = [
            (string)$obj->testlist->eduyear,
            (string)$obj->testlist->semester,
            (string)$obj->testlist->departmentid,
             ContStudent::where('STUDENTID',$obj->testlist->students->student->id)->get()->first()->SPECIALITYID,
            (string)$obj->testlist->disciplinevariantid,
            (string)$obj->testlist->modulevariantid,
            (string)$obj->testlist->modulenum,
            (string)$obj->testlist->discipline,
            (string)$obj->testlist->moduletheme,
        ];
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
