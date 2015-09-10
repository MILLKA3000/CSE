<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;

class Array_ extends Model
{
    static public function formXLSArrayGrade($obj){
        $data = [];
        foreach($obj as $d){
            foreach($d->students->student as $student){
                $data[] = [(string)$student->id,(string)$student->fio,'',(string)$d->groupnum,(string)$student->credits_cur];
            }
        }
        return $data;
    }

    static public function formXLSArrayToTableDecode($obj){
        $data = [];
        foreach($obj as $d){
            foreach($d->students->student as $student){
                $data[] = [(string)$student->id,(string)$student->fio];
            }
        }
        return $data;
    }
}
