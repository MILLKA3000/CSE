<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class XmlAgregate extends Model
{
    protected $guarded  = array('id');

    protected $table = 'xml_file_info';
}
