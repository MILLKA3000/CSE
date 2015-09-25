<?php

namespace App\Model\CreateDocuments;

use App\Grades;
use App\GradesFiles;
use App\Helper\File as HelperFile;
use App\Model\Contingent\Speciality;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Model;
use App\Model\Contingent\Students as ContStudent;
use File;


class Documents extends Model
{
    protected $DOC_PATH;

    protected $studentsOfGroup;

    protected $dataGradesOfStudentsGroups;

    protected $dataOfFile;

    protected $speciality;

    protected $idFileGrade;

    protected $shablon;

    public function __construct($idFileGrade)
    {
        $this->DOC_PATH = public_path().'/documents/'.HelperFile::_get_path().'/'.date('Y-m-d');
        $this->idFileGrade = $idFileGrade;
    }

    /**
     * Public func for prepare get data
     */
    public function formDocument(){

        $this->dataGradesOfStudentsGroups = Grades::select('group')->where('grade_file_id',$this->idFileGrade)->distinct()->get();
        $this->dataOfFile = GradesFiles::find($this->idFileGrade);
        $this->speciality = Speciality::where('SPECIALITYID',$this->dataOfFile->SpecialityId)->get()->first()->SPECIALITY;
        $this->formHtml();
        $files = glob($this->DOC_PATH.'/docs');
        Zipper::make($this->DOC_PATH.'/Docs.zip')->add($files);
        return $this->DOC_PATH.'/Docs.zip';
    }

    /**
     * Agregate functions for create shablon
     */
    private function formHtml()
    {
        foreach($this->dataGradesOfStudentsGroups as $group) {
            $this->studentsOfGroup = Grades::where('grade_file_id',$this->idFileGrade)->where('group',$group->group)->get();
            $this->putToShablon();
            File::makeDirectory($this->DOC_PATH.'/docs', 0775, true,true);
            File::put($this->DOC_PATH.'/docs/'.$group->group.'.doc', $this->shablon);
        }
    }

    /**
     * put data to shablon
     */
    private function putToShablon()
    {
        $this->createHeaderShablon();
        $num = 1;
        foreach($this->studentsOfGroup as $student) {
            $this->shablon.="<tr><td width=10%>".($num++)."</td><td width=50%>".$student->fio."</td><td width=15%>".ContStudent::where('STUDENTID',$student->id_student)->get()->first()->RECORDBOOKNUM."</td><td width=10%>".$student->exam_grade."</td></tr>";
        }
        $this->createFooterShablon();
    }

    /**
     * Convert semester to course
     * @return int
     */
    private function findSemester()
    {
        return ($this->dataOfFile->Semester & 1) ? ($this->dataOfFile->Semester+1)/2 : $this->dataOfFile->Semester/2;
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
        <span align=left> Факультет <u>" . iconv("Windows-1251", "UTF-8", $this->speciality) . "</u></span><br>
        <span align=left> Спеціальність <u>" . iconv("Windows-1251", "UTF-8", $this->speciality) . "</u></span>
        <span align=right>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Група_<u>" . $this->studentsOfGroup->first()->group . "</u>___</span>
        &nbsp;&nbsp;&nbsp;&nbsp;".$this->dataOfFile->EduYear . "/" . ($this->dataOfFile->EduYear + 1)." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Курс _<u>" . $this->findSemester() . "</u>___<br />
        <p align=center>ЕКЗАМЕНАЦІЙНА ВІДОМІСТЬ №____ </p>
        <p>З <u>" . $this->dataOfFile->ModuleNum . ". " . $this->dataOfFile->NameDiscipline . "</u> - <u>" . $this->dataOfFile->NameModule . "</u></p>
        <p>За _<u>" . $this->dataOfFile->Semester . "</u>___ навчальний семестр, екзамен <u>_" . date('d.m.Y') . "___</u></p>
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
        $this->shablon.="</table><br />
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
