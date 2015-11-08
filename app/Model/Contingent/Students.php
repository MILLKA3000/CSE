<?php

namespace App\Model\Contingent;

use App\Helper\Recoding;
use App\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Students extends Model
{
    protected $connection = 'firebird';

    protected $guarded  = array('STUDENTID');

    protected $table = 'STUDENTS';

    static private function getFromCache($id){
        return Cache::get($id);
    }

    static private function setToCache($id,$dataStudentFromContingent){
        return Cache::add($id,$dataStudentFromContingent,Setting::where('key','timeCache')->get()->first()->value);
    }

    static private function hasCache($id){
        return Cache::has($id);
    }

    static private function getStudentFromOtherDB($id){

        if (self::hasCache($id)){
            return self::getFromCache($id);
        }else{
            $dataStudentFromContingent = self::where('STUDENTID', $id)->get()->first();
            self::setToCache($id,$dataStudentFromContingent);
            return $dataStudentFromContingent;
        }

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

    static public function getStudentFIO($id){
        return Recoding::winToUtf(self::getStudentFromOtherDB($id)->FIO);
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