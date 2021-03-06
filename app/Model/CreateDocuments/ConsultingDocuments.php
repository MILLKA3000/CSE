<?php

namespace App\Model\CreateDocuments;

use App\AllowedDiscipline;
use App\CacheSpeciality;
use App\ConsultingGrades;
use App\Grades;
use App\GradesFiles;
use App\Model\Contingent\Students;
use App\UserToDepartments;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Model;
use App\Model\Contingent\Students as ContStudent;
use File;
use Illuminate\Support\Facades\Auth;
use Storage;
use App\CacheDepartment;


class ConsultingDocuments extends Model
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

    private $numStud;

    private $numModule = 1;

    private $gradePrint = 1;

    public function __construct($depId,$idFileGrade,$gradePrint = false)
    {
        $this->gradePrint = $gradePrint;

        /**
         * get data from bd about module (generals data for each docs)
         */
        $this->DOC_PATH = DIRECTORY_SEPARATOR.'consultingDocuments'.DIRECTORY_SEPARATOR;
        $this->dataEachOfFile = GradesFiles::where('ModuleVariantID', $idFileGrade)->where('DepartmentId', $depId)->get();

        Storage::deleteDirectory($this->DOC_PATH);
    }

    /**
     * Public func for prepare get data
     */
    public function formDocuments()
    {
        /**
         * find each student and sort of groupNum
         */
        $students = Grades::select('id_student')->whereIn('grade_file_id', (array) $this->dataEachOfFile->lists('id')->toArray())->distinct()->get();
        $this->speciality = CacheSpeciality::getSpeciality(Students::getStudentSpeciality($students[0]->id_student))->name;
        $this->department = CacheDepartment::getDepartment(Students::getStudentDepartment($students[0]->id_student))->name;
        foreach ($students as $student) {
            $student->grade_consulting = ConsultingGrades::where('id_student',$student['id_student'])->where('id_num_plan', $this->dataEachOfFile->first()->ModuleVariantID)->get()->last();
            (isset($student->grade_consulting))?$student->grade_consulting = $student->grade_consulting->grade_consulting:$student->grade_consulting='';
            $this->studentsOfGroup[Students::getStudentGroup($student['id_student'])][] = $student;
        }
        $this->formHtml();
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
            $this->createHeaderShablon($group);
            $num = 1;
            foreach($students as $student) {
                $student->num = $this->numStud = $num++;
                $student->grade_consulting = (($this->gradePrint=="true")?(isset($student->grade_consulting))?($student->grade_consulting=='0')?'0(не склав)':$student->grade_consulting:'':'');
                $this->shablon .= view('admin.docs.consulting.general')->with('student',$student);
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
        return ($this->dataEachOfFile->first()->Semester & 1) ? ($this->dataEachOfFile->first()->Semester + 1) / 2 : $this->dataEachOfFile->first()->Semester / 2;
    }

    /**
     * Create block for header of shablon
     */
    private function createHeaderShablon()
    {
        $this->semester = $this->findSemester();
        $this->date = date('d.m.Y');
        $this->shablon .= view('admin.docs.consulting.header')->with('this',$this);
    }

    /**
     * Create block for footer of shablon
     */
    private function createFooterShablon()
    {
        $this->shablon .= view('admin.docs.consulting.footer')->with('count',$this->numStud);
    }


}
