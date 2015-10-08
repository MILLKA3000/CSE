<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Model\Contingent\Speciality;

class CacheSpeciality extends Model
{
    protected $guarded = array('id');

    protected $table = 'cache_speciality';

    public $timestamps = false;

    static public function getSpeciality($id)
    {
        $speciality = self::where('id_from', $id)->get()->first();
        if (isset($speciality)) {
            return $speciality;
        } else {
            $name = iconv("Windows-1251", "UTF-8", Speciality::where('SPECIALITYID', $id)->get()->first()->SPECIALITY);
            self::create(['id_from' => $id, 'name' => $name]);
            return self::where('id_from',$id)->get()->first();
        }
    }
}
