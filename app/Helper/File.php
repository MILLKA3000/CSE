<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use Auth;

class File extends Model
{
    static function _get_path(){
      return  Auth::user()->roles->name.'/'.Auth::user()->name;
    }

}
