<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;

class Recoding extends Model
{
    static public function winToUtf($str){
        $str = iconv("windows-1251","utf-8",$str);
        return $str;
    }
}
