<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileInfo extends Model
{
    protected $guarded  = array('id');

    protected $table = 'xls_file_info';

    private $rules = array(
        'name' => 'required|min:2',
    );
}
