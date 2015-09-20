<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeExam extends Model
{
    protected $guarded  = array('id');

    protected $table = 'type_exam';

    public $timestamps = false;

}
