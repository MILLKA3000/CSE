<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowedDiscipline extends Model
{
    protected $guarded  = array('id');

    protected $table = 'allowed_discipline';

    protected $dates = ['deleted_at'];
}
