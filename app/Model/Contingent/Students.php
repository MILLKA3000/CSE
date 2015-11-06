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
    static public function getStudentDepartment($id){
        return self::getStudentFromOtherDB($id)->DEPARTMENTID;
    }
    static public function getStudentGroup($id){
        return self::getStudentFromOtherDB($id)->GROUPNUM;
    }
    static public function getSumContractOrButjetStudent($students){
        $basisid = ['C'=>0,'B'=>0];
        foreach($students as $student){
        $getEduBasisisStudent = (iconv("Windows-1251", "UTF-8",self::getStudentFromOtherDB($student['id_student'])->EDUBASISID)=='К')?'C':'B';
            $basisid[$getEduBasisisStudent]++;
        }
        return $basisid;
    }

}