<?php

namespace App\Model\Contingent;

use Illuminate\Database\Eloquent\Model;
use App\Helper\Recoding;

class Departments extends Model
{
    protected $connection = 'firebird';

    protected $guarded  = array('DEPARTMENTID');

    protected $table = 'GUIDE_DEPARTMENT';

    static public function getAllDepartment(){

        $departments = Departments::select()->where('USE','=',1)->get();

        foreach($departments as $department){
            $department->DEPARTMENT = Recoding::winToUtf($department->DEPARTMENT);
        }

        return $departments;
    }

}