<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container;
use Orchestra\Parser\Xml\Document;
use Orchestra\Parser\Xml\Reader;

class XML extends Model
{
    protected $app;
    protected $document;
    protected $stub;
    protected $xml;

    public function __construct()
    {
        $this->app      = new Container();
        $this->document = new Document($this->app);
        $this->stub     = new Reader($this->document);
    }

    public function parseFromUrl($url){
        return $this->xml = $this->stub->load($url);
    }

    public function countStudents(){
        $students_q = 0;
        foreach($this->xml->getContent() as $d){
            foreach($d->students->student as $student){
                $students_q++;
            }
        }

        return $students_q;
    }
}
