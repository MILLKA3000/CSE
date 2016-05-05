<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded  = array('id');

    protected $table = 'settings';

    protected $dates = ['deleted_at'];
}
