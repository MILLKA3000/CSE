<?php

namespace App\Helper;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Auth;

class File extends Model
{
    /**
     * create path role/user/timestampDirectory/
     * @return string
     */
    static function _get_path(){
        $date = new DateTime();
        return  Auth::user()->roles->name.'\\'.Auth::user()->name.'\\'.$date->getTimestamp();
    }

    /**
     *
     * @param $fullPath = example('type/role/user/timestampDirectory/nameFile.type')
     * @return string
     */
    static function _getTimestampPath($fullPath){
        $pathArr = explode('/',$fullPath);
        if (count($pathArr)==1) $pathArr = explode('\\',$fullPath);
        unset($pathArr[4]);
        unset($pathArr[0]);
        return implode('/',$pathArr);
    }

}
