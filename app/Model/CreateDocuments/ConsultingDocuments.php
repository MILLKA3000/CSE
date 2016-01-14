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
            $this->createHeaderShablon($group);
            $num = 1;
            foreach($students as $student) {
                $this->shablon .= "<tr><td width=10% align=center>" . ($num++) . "</td><td width=50%>" . Students::getStudentFIO($student->id_student) . "</td><td width=15%>" . ContStudent::getStudentBookNum($student->id_student) . "</td><td width=10%>".(($this->gradePrint=="true")?(isset($student->grade_consulting))?($student->grade_consulting=='0')?'0(не склав)':$student->grade_consulting:'':'')."</td><td></td></tr>";
            }
            $this->numStud = $num-1;
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
        <p align=center><b><u>ДВНЗ «Тернопільський державний медичний університет імені І.Я. Горбачевського МОЗ України</u></b></p>
        <table class=guestbook width=625 align=center cellspacing=0 cellpadding=3 border=0>
            <tr>
                <td width=80%> Факультет <u>" . $this->department . "</u></td><td>Група_<u>" . $group . "</u>___</td>
            </tr>
            <tr>
                <td width=80%> <u>" . $this->dataEachOfFile->first()->EduYear . "/" . ($this->dataEachOfFile->first()->EduYear + 1) . "</u> навчальний рік</td><td>Курс _<u>" . $this->findSemester() . "</u>___</td>
            </tr>
            <tr>
                <td width=80%>  Спеціальність <u>" . $this->speciality . "</u></td><td></td>
            </tr>
        </table>
        <p align=center> ЕКЗАМЕНАЦІЙНА ВІДОМІСТЬ УСНОЇ СПІВБЕСІДИ №__________ </p>
        <p>З <u>" . $this->dataEachOfFile->first()->ModuleNum . ". " . $this->dataEachOfFile->first()->NameDiscipline . "</u> - <u>" . $this->dataEachOfFile->first()->NameModule . "</u></p>
        <table class=guestbook width=625 align=center cellspacing=0 cellpadding=3 border=0>
            <tr>
                <td width=30%>За _<u>" . $this->dataEachOfFile->first()->Semester . "</u>___ навчальний семестр,</td><td width=20%><u>_" . date('d.m.Y') . "___</u></td><td width=50%></td>
            </tr>
            <tr>
                <td width=30%></td><td width=20% style='font-size: 60%'>(дата усної співбесіди)</td><td width=50%></td>
            </tr>
        </table>
        <table class=guestbook width=625 align=center cellspacing=0 cellpadding=3 border=0>
            <tr>
                <td width=57%> Викладач(і), який(і) проводить(ять) усну співбесіду </td><td width=43%>_________________________________________</td>
            </tr>
            <tr>
                <td width=60%></td><td width=40% style='font-size: 60%'>(вчене звання, прізвище та ініціали)</td>
            </tr>
        </table>
        <table class=guestbook width=620 align=center cellspacing=0 cellpadding=3 border=1>
            <tr>
                <td width=5% align=center>
                    <b>№ <br />п/п</b>
                </td>
                <td width=50%>
                    <b>Прізвище, ім'я по-батькові</b>
                </td>
                <td width=10% align=center>
                    <b>№ індиві-дуального навч. плану</b>
                </td>
                <td width=15% align=center>
                    <b>Оцінка за співбесіду</b>
                </td>
                <td width=10%>
                    <b>Підпис екзаме-натора(рів)</b>
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
        $this->shablon .= "

        </table>
        <br />
        <table class=guestbook width=625 align=center cellspacing=0 cellpadding=3 border=0>
            <tr>
                <td width=25%>Студентів у групі</td><td width=10%>___<u>".$this->numStud."</u>___</td><td width=20%></td><td width=12%>Екзаменатор(и)</td><td width=13%>________________</td>
            </tr>
            <tr>
                <td width=25%>Не допущено</td><td width=10%>________</td><td width=20%></td><td width=12%></td><td width=13% ><span align=center style='font-size: 60%'>(підпис) </span></td>
            </tr>
            <tr>
                <td width=25%>Не з’явилось</td><td width=10%>________</td><td width=20%></td><td width=12%>Зав. кафедри</td><td width=13%>________________</td>
            </tr>
            <tr>
                <td width=25%>Декан факультету</td><td width=20%>________________________</td><td width=10%></td><td width=12%></td><td width=13% ><span align=center style='font-size: 60%'>(підпис) </span></td>
            </tr>
            <tr>
                <td width=25%></td><td width=10%><span align=center style='font-size: 60%'>(прізвище та ініціали) </span></td>
            </tr>
            <tr>
                <td width=25%></td><td width=10%>________</td>
            </tr>
            <tr>
                <td width=25%></td><td width=10%><span align=center style='font-size: 60%'>(підпис)</span></td>
            </tr>
        </table><br />
        1.       Проти прізвища студента, який не з’явився на підсумковий контроль, екзаменатор вказує – „не з’явився”.<br />
        2.       Відомість подається в деканат в день проведення усної співбесіди.";
    }


}
