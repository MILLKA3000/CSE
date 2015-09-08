<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Logs extends Model
{
    protected $guarded  = array('id');

    protected $table = 'logs';

    static function _create($title){
        Logs::create(['user_id'=>Auth::user()->id,'title'=>$title]);
    }

}
