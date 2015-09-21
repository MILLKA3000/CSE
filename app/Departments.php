<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $guarded  = array('id');

    protected $table = 'departments';

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    public function getUser(){
        return $this->hasOne('App\UserToDepartments','id')->get()->first();
    }

    public function getNameUser(){
        return $this->hasOne('App\User','id')->get()->first();
    }

}
