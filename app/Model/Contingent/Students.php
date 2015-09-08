<?php

namespace App\Model\Contingent;

use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    protected $connection = 'firebird';

    protected $guarded  = array('STUDENTID');

    protected $table = 'STUDENTS';




}