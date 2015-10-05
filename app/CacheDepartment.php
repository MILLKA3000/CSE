<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model\Contingent\Departments;

class CacheDepartment extends Model
{
    protected $guarded  = array('id');

    protected $table = 'cache_department';

    public $timestamps = false;

    static public function getDepartment($id)
    {
        $department = self::where('id_from',$id)->get()->first();
        if ($department){
            return $department;
        }else{
            $name = iconv("Windows-1251", "UTF-8",Departments::where('DEPARTMENTID',$id)->get()->first()->DEPARTMENT);
            self::create(['id_from'=>$id,'name'=>$name]);
            return self::where('id_from',$id)->get()->first();
        }
    }
}
