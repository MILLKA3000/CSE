<?php

namespace App\Model\CreateDocuments;

use App\GradesFiles;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{

    protected $DOC_PATH;

    protected $idFileGrade = 0;

    protected $dataOfFile;

    public function __construct($idFileGrade)
    {
        $this->idFileGrade = $idFileGrade;
        $this->dataOfFile = GradesFiles::find($this->idFileGrade);
        $this->DOC_PATH = '\documents\\'.$this->dataOfFile->get_path()->get()->first()->path;
    }


    /**
     * Public func for prepare get data
     */
    public function formDocuments(){

//        $this->dataGradesOfStudentsGroups = Grades::select('group')->where('grade_file_id',$this->idFileGrade)->distinct()->get();
        Zipper::make(public_path().$this->DOC_PATH.'\Stat.zip')->add(glob(public_path().$this->DOC_PATH.'\stat'));
        return $this->DOC_PATH.'\Stat.zip';

    }


}
