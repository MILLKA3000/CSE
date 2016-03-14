<?php

namespace App\Model\CreateDocuments;

use App\CacheSpeciality;
use App\Grades;
use App\GradesFiles;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Model;
use App\Model\Contingent\Students as ContStudent;
use File;
use Illuminate\Support\Facades\Session;
use Storage;
use App\CacheDepartment;
use Illuminate\Http\Request;
use App\Http\Requests;


class Documents extends Model
{
    protected $DOC_PATH; // for puts files

    protected $studentsOfGroup; // Students of group

    protected $dataGradesOfStudentsGroups; // for find all groups

    protected $dataOfFile; // each module

    protected $dataEachOfFile; // select module

    protected $speciality; // get from cache speciality

    protected $department; // get from cache department

    protected $idFileGrade = 0;

    protected $shablon;

    private $numModule = 1;

    public function __construct($idFileGrade)
    {
        $this->dataOfFile = GradesFiles::where('file_info_id',$idFileGrade)->get();

        /**
         * get data from bd about module (generals data for each docs)
         */
        $this->DOC_PATH = DIRECTORY_SEPARATOR.'documents'.DIRECTORY_SEPARATOR . $this->dataOfFile[0]->get_path()->get()->first()->path;
        $this->speciality = CacheSpeciality::getSpeciality($this->dataOfFile[0]->SpecialityId)->name;
        $this->department = CacheDepartment::getDepartment($this->dataOfFile[0]->DepartmentId)->name;

        Storage::deleteDirectory($this->DOC_PATH);
//        Storage::delete($this->DOC_PATH . '\Docs.zip');
    }

    /**
     * Public func for prepare get data
     */
    public function formDocuments()
    {
        foreach($this->dataOfFile as $this->dataEachOfFile) {
            $this->studentsOfGroup = [];
            $this->dataGradesOfStudentsGroups = Grades::where('grade_file_id', $this->dataEachOfFile->id)->get();
            foreach ($this->dataGradesOfStudentsGroups as $student) {
                $this->studentsOfGroup[$student['group']][] = $student;
            }
            $this->formHtml();
            $this->numModule++;
        }
        Zipper::make(public_path() . $this->DOC_PATH . DIRECTORY_SEPARATOR.'Docs.zip')->add(glob(public_path() . $this->DOC_PATH . DIRECTORY_SEPARATOR.'docs'));
        return $this->DOC_PATH . DIRECTORY_SEPARATOR.'Docs.zip';
    }

    /**
     * Agregate functions for create shablon
     */
    private function formHtml()
    {
        foreach ($this->studentsOfGroup as $group=>$students) {
            $this->shablon = '';
            $this->group = $group;
            $this->createHeaderShablon();
            $num = 0;
            foreach($students as $student) {
                $student->num = ++$num;
                $student->exam_grade = ($student->exam_grade == 0) ? "0(не склав)" : $student->exam_grade;
                $student->exam_grade = ($student->code == 999) ? "(не з'явився)" :  $student->exam_grade;
                $this->shablon .= view('admin.docs.exam.general')->with('student',$student);
            }
            $this->createFooterShablon();
            File::makeDirectory(public_path() . $this->DOC_PATH . DIRECTORY_SEPARATOR.'docs', 0775, true, true);
            File::put(public_path() . $this->DOC_PATH . DIRECTORY_SEPARATOR.'docs'.DIRECTORY_SEPARATOR . $this->numModule . '.' . $group . '.doc', $this->shablon);
        }
    }

    /**
     * Convert semester to course
     * @return int
     */
    private function findSemester()
    {
        return ($this->dataEachOfFile->Semester & 1) ? ($this->dataEachOfFile->Semester + 1) / 2 : $this->dataEachOfFile->Semester / 2;
    }

    /**
     * Create block for header of shablon
     */
    private function createHeaderShablon()
    {
        $this->semester = $this->findSemester();
        $this->date = date('d.m.Y');
        $this->shablon .= view('admin.docs.exam.header')->with('this',$this);
    }

    /**
     * Create block for footer of shablon
     */
    private function createFooterShablon()
    {
        $this->shablon .= view('admin.docs.exam.footer');
    }


}
