<?php

namespace App\Model\CreateDocuments;

use App\CacheSpeciality;
use App\Grades;
use App\GradesFiles;
use App\Helper\File as HelperFile;
use App\Model\Contingent\Departments;
use App\Model\Contingent\Speciality;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Model;
use App\Model\Contingent\Students as ContStudent;
use File;
use App\CacheDepartment;


class Documents extends Model
{
    protected $DOC_PATH;

    protected $studentsOfGroup;

    protected $dataGradesOfStudentsGroups;

    protected $dataOfFile;

    protected $dataEachOfFile;

    protected $speciality;

    protected $department;

    protected $idFileGrade = 0;

    protected $shablon;

    private $numModule = 1;

    public function __construct($idFileGrade)
    {
        $this->idFileGrade = $idFileGrade;
        $this->dataOfFile = GradesFiles::where('file_info_id',$this->idFileGrade)->get();
        $this->DOC_PATH = '\documents\\' . $this->dataOfFile[0]->get_path()->get()->first()->path;
    }

    /**
     * Public func for prepare get data
     */
    public function formDocuments()
    {
        foreach($this->dataOfFile as $dataOfFile) {
            $this->dataEachOfFile = $dataOfFile;
            $this->dataGradesOfStudentsGroups = Grades::select('group')->where('grade_file_id', $this->idFileGrade)->distinct()->get();
            $this->speciality = CacheSpeciality::getSpeciality($dataOfFile->SpecialityId)->name;
            $this->department = CacheDepartment::getDepartment($dataOfFile->DepartmentId)->name;
            $this->formHtml();
            $this->numModule++;
        }
        Zipper::make(public_path() . $this->DOC_PATH . '\Docs.zip')->add(glob(public_path() . $this->DOC_PATH . '\docs'));
        return $this->DOC_PATH . '\Docs.zip';

    }

    /**
     * Agregate functions for create shablon
     */
    private function formHtml()
    {
        foreach ($this->dataGradesOfStudentsGroups as $group) {
            $this->studentsOfGroup = Grades::where('grade_file_id', $this->idFileGrade)->where('group', $group->group)->get();
            $this->putToShablon();
            File::makeDirectory(public_path() . $this->DOC_PATH . '\docs', 0775, true, true);
            File::put(public_path() . $this->DOC_PATH . '\docs\\' .$this->numModule.'.'. $group->group . '.doc', $this->shablon);
        }
    }

    /**
     * put data to shablon
     */
    private function putToShablon()
    {
        $this->createHeaderShablon();
        $num = 1;
        foreach ($this->studentsOfGroup as $student) {
            $student->exam_grade = ($student->exam_grade == 0) ? "0(не склав)" : $student->exam_grade;
            $student->exam_grade = ($student->code == 999) ? "(не з'явився)" : $student->exam_grade;
            $this->shablon .= "<tr><td width=10%>" . ($num++) . "</td><td width=50%>" . $student->fio . "</td><td width=15%>" . ContStudent::where('STUDENTID', $student->id_student)->get()->first()->RECORDBOOKNUM . "</td>
            <td width=10%>" . $student->exam_grade . "</td></tr>";
        }
        $this->createFooterShablon();
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
        <span align=right>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Група_<u>" . $this->studentsOfGroup->first()->group . "</u>___</span>
        &nbsp;&nbsp;&nbsp;&nbsp;" . $this->dataEachOfFile->EduYear . "/" . ($this->dataEachOfFile->EduYear + 1) . " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Курс _<u>" . $this->findSemester() . "</u>___<br />
        <p align=center>ЕКЗАМЕНАЦІЙНА ВІДОМІСТЬ №____ </p>
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
