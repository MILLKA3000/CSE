<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradesFiles extends Model
{
    protected $guarded  = array('id');

    protected $table = 'grades_files';

    public function get_path()
    {
        return $this->hasOne('App\FileInfo','id','file_info_id');
    }

    public function getUserId()
    {
        return $this->hasOne('App\FileInfo','id')->get()->first()->user_id;
    }

    public function getConsultingGrades(){
        return $this->hasMany('App\ConsultingGrades','id_num_plan','ModuleVariantID');
    }

    public function getGrades(){
        return $this->hasMany('App\Grades','grade_file_id','id');
    }
}
