<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class Excel_ extends Model
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function _getMockup()
    {
        Excel::create('Filename', function($excel) {

            $excel->sheet('Exam grades', function($sheet) {
            });

            $excel->sheet('Table decode', function($sheet) {
                $sheet->fromArray(Array_::formXLSArrayToTableDecode($this->data));
                $sheet->row(1, array('ID', 'FIO','KODE'));
                $sheet->setAutoSize(true);
            });

            $excel->sheet('Grades', function($sheet) {
                $sheet->fromArray(Array_::formXLSArrayGrade($this->data));
                $sheet->row(1, array('ID', 'FIO','Grades'));
                $sheet->setAutoSize(true);
            });
        })->export('xls');
    }

    static public function _loadXls($url){
        $data = Excel::load($url, function($reader) {
           return $reader->all();
        });

        return $data;
    }

}
