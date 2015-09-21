<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToDepartments extends Model
{
    protected $guarded  = array('id');

    protected $table = 'user_to_departament';

    public $timestamps = false;
}
