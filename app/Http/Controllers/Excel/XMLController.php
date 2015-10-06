<?php

namespace App\Http\Controllers\Excel;

use App\Logs;
use App\XmlAgregate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helper\XML;
use App\Helper\File as fileHelp;
use App\Helper\Excel_;
use Chumper\Zipper\Facades\Zipper;
use Storage;
use File;
use Auth;


class XMLController extends Controller
{
    private $xml;

    private $path;

    private $AllFilespath;

    protected $url;

    protected $data;

    public function __construct()
    {
        $this->middleware('role:Admin');
        view()->share('type', 'work');

        $this->path = 'xml\\'.fileHelp::_get_path();
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

            if (count($file)>1){
                $files = [];
                foreach($file as $f){
                    if ($f->getMimeType() == 'application/xml') {
                        $this->moveFile($f);
                    }
                }
                $this->foreachFileParce($files = Storage::files(str_replace ( '\\' ,'/' , $this->path)));
            }elseif (!is_null($file[0])) {
                if ($file[0]->getMimeType() == 'application/xml') {
                    $this->moveFile($file[0]);
                    $this->_parce($this->url);
                    $this->AllFilespath[] = $this->url->getPathName();
                } elseif ($file[0]->getMimeType() == 'application/zip') {
                    $this->moveFile($file[0]);
                    $this->_parceZIP($file[0]);
                }
            }else {
                    return view('admin.xml.loadxml')->with(['error'=>'You uploaded not this type files.<br> You must upload files of type [xml(single or multiple), zip(only single)]']);
            }
            $this->saveLog();
            return view('admin.xml.view', ['data' => $this->data, 'files' => $this->saveArchiveXml()]);
        }else{
            return view('admin.xml.loadxml');
        }

    }

    public function viewXml($xml_path)
    {
        $this->_parce($xml_path);
        return view('admin.xml.view', ['data'=>$this->data]);
    }

    public function downloadXLS($id){
        $excel = new Excel_(XmlAgregate::where('id',$id)->get()->first());
        $excel->_getMockup();
    }

    public function _parce($xml_path){
        $this->xml = new XML();
        $data['data'] = $this->xml->parseFromUrl($xml_path);
        $data['student'] = $this->xml->countStudents();
        $this->data[] = $data;

    }


    private function _parceZIP($file){
        $pathZip = $this->path;
        File::makeDirectory($pathZip.'\xml', 0775, true,true);
        Zipper::make($pathZip.'\\'.$file->getClientOriginalName())->extractTo($pathZip.'\xml\\');
        $files = Storage::files(str_replace ( '\\' ,'/' , $pathZip.'\xml\\' ));

        $this->foreachFileParce($files);
    }


    private function foreachFileParce($files){
        foreach($files as $file){
            $this->_parce($file);
            $this->AllFilespath[] = $file;
        }
    }


    /**
     * @param $file
     */
    private function moveFile($file){
        $this->url = $file->move($this->path, $file->getClientOriginalName());
    }

    /**
     * Save Log
     */
    private function saveLog(){
        Logs::_create('User parse XML '.$this->url);
    }

    /**
     * @return mixed
     */
    private function saveArchiveXml(){
        $id = XmlAgregate::create([
            'files_path'=> json_encode($this->AllFilespath),
            'user_id'=>Auth::user()->id
        ]);

        return $id->id;
    }

    public function getData(){
        return $this->data;
    }
}
