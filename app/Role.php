<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded  = array('id');


    private $rules = array(
        'name' => 'required|min:2',
    );
}
