<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use App\Http\Controllers\Excel\XMLController;

class Excel_ extends Model
{

    protected $data;

    protected $filesXml;

    public function __construct($data)
    {
        $this->filesXml = json_decode($data->files_path);
        $parce = new XMLController();

        foreach($this->filesXml as $file){
            $parce->_parce($file);
        }

        $this->data = $parce->getData();
    }


    public function _getMockup()
    {
        Excel::create('Mockup('.(Auth::user()->name).')', function($excel) {

            $excel->sheet('Exam grades', function($sheet) {
            });

            $excel->sheet('Table decode', function($sheet) {
                $sheet->fromArray(Array_::formXLSConnectTable($this->data[0]['data']));
                $sheet->row(1, array('ID', 'FIO','Kode','Group'));
                $sheet->setAutoSize(true);
            });

            $excel->sheet('Module Grades', function($sheet) {
                $sheet->fromArray(Array_::formXLSArrayGrade($this->data));
                $sheet->row(1, array('ID', 'FIO'));
                $sheet->setAutoSize(true);
            });

            $excel->sheet('Table Info', function($sheet) {
                $sheet->fromArray(Array_::getInfoForXLS($this->data));
                $sheet->row(1, array(
                    'EduYear',
                    'Semester',
                    'DepartmentId',
                    'SpecialityId',
                    'DisciplineVariantID',
                    'ModuleVariantID',
                    'ModuleNum',
                    'NameDiscipline',
                    'NameModule',
                ));
                $sheet->setAutoSize(false);
            });
        })->export('xlsx');
    }

    static public function _loadXls($url){
        $data = Excel::load($url, function($reader) {
           return $reader->all();
        });

        return $data;
    }

}
