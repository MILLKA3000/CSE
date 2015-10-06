<?php

namespace App\Model\Contingent;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    protected $connection = 'firebird';

    protected $guarded  = array('STUDENTID');

    protected $table = 'STUDENTS';


    static private function getStudentFromOtherDB($id){
        return self::where('STUDENTID', $id)->get()->first();
    }

    static public function getStudentBookNum($id){
        return self::getStudentFromOtherDB($id)->RECORDBOOKNUM;
    }

    static public function getStudentSpeciality($id){
        return self::getStudentFromOtherDB($id)->SPECIALITYID;
    }

}