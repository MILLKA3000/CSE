<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradesFiles extends Model
{
    protected $guarded  = array('id');

    protected $table = 'grades_files';

    public function get_path()
    {
        return $this->hasOne('App\FileInfo','id');
    }
}
