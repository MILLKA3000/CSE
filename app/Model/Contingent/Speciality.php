<?php

namespace App\Model\Contingent;

use Illuminate\Database\Eloquent\Model;
use App\Helper\Recoding;

class Speciality extends Model
{
    protected $connection = 'firebird';

    protected $guarded  = array('SPECIALITYID');

    protected $table = 'GUIDE_SPECIALITY';

    static public function getAllSpeciality(){

        $specialities = Speciality::select()->where('USE','=',1)->get();

        foreach($specialities as $speciality){
            $speciality->SPECIALITYCODE = Recoding::winToUtf($speciality->SPECIALITY)." ( ".$speciality->CODE." )";
        }

        return $specialities;
    }
}
