<?php

namespace App\Http\Controllers\Excel;

use App\Logs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helper\XML;
use App\Helper\File as file;
use App\Helper\Excel_;

class ExcelController extends Controller
{

    protected $url;
    protected $data;

    public function __construct()
    {
        $this->middleware('role:Admin');
        view()->share('type', 'work');
    }

    public function loadXml(Request $request)
    {
        if ($file = $request->file('xml')) {

            $xml = new XML();

            if ($file->getMimeType() == 'application/xml') {

                $this->url = $file->move('xml/'.file::_get_path(), $file->getFilename());

                $data = $xml->parseFromUrl($this->url);
                $data->count_student = $xml->countStudents();

                Logs::_create('User parse XML '.$this->url);

                $excel = new Excel_($data->getContent());
                $excel->_getMockup();
                
                return view('admin.excel.load', compact('data'));
            } else {
                return view('admin.xml.loadxml');
            }
        }else{
            return view('admin.xml.loadxml');
        }

    }

    public function viewXml($xml_path)
    {
        $xml = new XML();
        $data = $xml->parseFromUrl($xml_path);
        $data->count_student = $xml->countStudents();
        return view('admin.excel.load', compact('data'));
    }


}
