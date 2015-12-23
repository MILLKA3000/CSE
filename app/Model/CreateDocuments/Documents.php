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
            $this->createHeaderShablon($group);
            $num = 1;
            foreach($students as $student) {
                $exam_grade = ($student->exam_grade == 0) ? "0(не склав)" : $student->exam_grade;
                $exam_grade = ($student->code == 999) ? "(не з'явився)" : $exam_grade;
                $this->shablon .= "<tr><td width=10%>" . ($num++) . "</td><td width=50%>" . $student->fio . "</td><td width=15%>" . (is_int(ContStudent::getStudentBookNum($student->id_student))?ContStudent::getStudentBookNum($student->id_student):'' . "</td><td width=10%>" . $exam_grade . "</td></tr>";
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
        <p align=center>ЕКЗАМЕНАЦІЙНА ВІДОМІСТЬ №____ </p>
        <p>З <u>" . $this->dataEachOfFile->ModuleNum . ". " . $this->dataEachOfFile->NameDiscipline . "</u> - <u>" . $this->dataEachOfFile->NameModule . "</u></p>
        <p>За _<u>" . $this->dataEachOfFile->Semester . "</u>___ навчальний семестр, екзамен <u>_" . ((Session::has('date')) ? Session::get('date') : date('d.m.Y')) . "___</u></p>
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
            </tr>
        ";
    }

    /**
     * Create block for footer of shablon
     */
    private function createFooterShablon()
    {
        $this->shablon .= "</table><br />
        Голова комісії _______________________________________________________________ <br>
        (вчені звання, прізвище та ініціали)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(підпис)<br />
        Члени комісії ________________________________________________________________ <br>
        (вчені звання, прізвище та ініціали)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(підпис)<br />
        ____________________________________________________________________________ <br><br>
        ____________________________________________________________________________ <br><br>

        1. Проти прізвища студента, який не з’явився  на підсумковий контроль, екзаменатор вказує – „не з’явився”.<br>
        2. Відомість подається в деканат не пізніше наступного дня після проведення підсумкового контролю.
         ";
    }


}
