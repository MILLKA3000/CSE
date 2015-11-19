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

    private $numModule = 1;

    private $gradePrint = 1;

    public function __construct($idFileGrade,$gradePrint = false)
    {
        $this->gradePrint = $gradePrint;

        /**
         * get data from bd about module (generals data for each docs)
         */
        $this->DOC_PATH = DIRECTORY_SEPARATOR.'consultingDocuments'.DIRECTORY_SEPARATOR;
        $this->dataEachOfFile = GradesFiles::where('ModuleVariantID', $idFileGrade)->get()->first();

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
        $students = Grades::where('grade_file_id',$this->dataEachOfFile->id)->get();
        $this->speciality = CacheSpeciality::getSpeciality(Students::getStudentSpeciality($students[0]->id_student))->name;
        $this->department = CacheDepartment::getDepartment(Students::getStudentDepartment($students[0]->id_student))->name;
        foreach ($students as $student) {
            $student->grade_consulting = ConsultingGrades::where('id_student',$student['id_student'])->get()->first();
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
            $this->createHeaderShablon($group);
            $num = 1;
            foreach($students as $student) {
                $this->shablon .= "<tr><td width=10%>" . ($num++) . "</td><td width=50%>" . Students::getStudentFIO($student->id_student) . "</td><td width=15%>" . ContStudent::getStudentBookNum($student->id_student) . "</td><td width=10%>".(($this->gradePrint=="true")?$student->grade_consulting:'')."</td><td></td></tr>";
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
    private function createHeaderShablon($group)
    {
        $this->shablon = "
        <html>
        <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
            <style>
                body {font-size:14px;}
            </style>
        </head>
        <body>
        <p align=center>МІНІСТЕРСТВО ОХОРОНИ ЗДОРОВЯ УКРАЇНИ </p>
        <p align=center><b><u>Тернопільський державний медичний університет імені І.Я. Горбачевського</u></b></p>
        <span align=left> Факультет <u>" . $this->department . "</u></span><br>
        <span align=left> Спеціальність <u>" . $this->speciality . "</u></span>
        <span align=right>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Група_<u>" . $group . "</u>___</span>
        &nbsp;&nbsp;&nbsp;&nbsp;" . $this->dataEachOfFile->EduYear . "/" . ($this->dataEachOfFile->EduYear + 1) . " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Курс _<u>" . $this->findSemester() . "</u>___<br />
        <p align=center>ВІДОМІСТЬ №____ </p>
        <p>З <u>" . $this->dataEachOfFile->ModuleNum . ". " . $this->dataEachOfFile->NameDiscipline . "</u> - <u>" . $this->dataEachOfFile->NameModule . "</u></p>
        <p>За _<u>" . $this->dataEachOfFile->Semester . "</u>___ навчальний семестр, екзамен <u>_" . date('d.m.Y') . "___</u></p>
        <table class=guestbook width=600 align=center cellspacing=0 cellpadding=3 border=1>
            <tr>
                <td width=10%>
                    <b>№ п/п</b>
                </td>
                <td width=50%>
                    <b>Прізвище, ім'я по-батькові</b>
                </td>
                <td width=10%>
                    <b>№ індиві-дуального навч. плану</b>
                </td>
                <td width=10%>
                    <b>Кількість балів</b>
                </td>
                <td width=10%>
                    <b>Підпис викладача</b>
                </td>
            </tr>
        ";
    }

    /**
     * Create block for footer of shablon
     */
    private function createFooterShablon()
    {
        $name = UserToDepartments::select('name')->where('user_id',Auth::user()->id)->join('departments','departments.id','=','user_to_departament.departments_id')->get()->first();
        $this->shablon .= "</table><br />
        Завідувач кафедри (".(($name)?$name->name:'___________________________________________________________________________________________________').")
        <br>_______________________________________________________________ <br>
        (вчені звання, прізвище та ініціали)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(підпис)<br />";
    }


}
