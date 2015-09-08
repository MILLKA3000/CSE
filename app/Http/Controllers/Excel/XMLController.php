<?php

namespace App\Http\Controllers\Excel;

use App\Logs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helper\XML;
use App\Helper\File as file;
use App\Helper\Excel_;


class XMLController extends Controller
{
    private $xml;

    protected $url;

    protected $data;

    public function __construct()
    {
        $this->xml = new XML();
        $this->middleware('role:Admin');
        view()->share('type', 'work');
    }

    /**
     * parse xml
     *
     * @param Request $request
     * @return \BladeView|bool|\Illuminate\View\View
     */
    public function loadXml(Request $request)
    {
        if ($file = $request->file('xml')) {

            if ($file->getMimeType() == 'application/xml') {

                $this->url = $file->move('xml/'.file::_get_path(), $file->getFilename());
                $this->_parce($this->url);
                Logs::_create('User parse XML '.$this->url);
                $this->data->file_name = $file->getFilename();
                return view('admin.xml.view', ['data'=>$this->data]);
            } else {
                return view('admin.xml.loadxml')->with(['error'=>'No type file']);
            }
        }else{
            return view('admin.xml.loadxml');
        }

    }

    public function viewXml($xml_path)
    {
        $this->_parce($xml_path);
        return view('admin.xml.view', ['data'=>$this->data]);
    }

    public function downloadXLS($xml_file){
        $this->_parce('xml/'.file::_get_path().'/'.$xml_file);
        $excel = new Excel_($this->data->getContent());
        $excel->_getMockup();
    }

    private function _parce($xml_path){
        $this->data = $this->xml->parseFromUrl($xml_path);
        $this->data->count_student = $this->xml->countStudents();
    }
}
