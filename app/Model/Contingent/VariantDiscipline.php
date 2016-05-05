<?php

namespace App\Model\Contingent;

use App\Helper\Recoding;
use Illuminate\Database\Eloquent\Model;

class VariantDiscipline extends Model
{
    protected $connection = 'firebird';

    protected $guarded  = array('VARIANTID');

    protected $table = 'B_VARIANT_DISCIPLINE';

    static public function getFormReport($id){
        return Recoding::winToUtf(self::where('VARIANTID', $id)->get()->first()->FORMREPORT);
    }
}
