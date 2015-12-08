<?php

namespace App\Model\CreateDocuments;

use App\AllowedDiscipline;
use App\CacheDepartment;
use App\CacheSpeciality;
use App\GradesFiles;
use App\Model\Contingent\Students;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Model;
use App\Grades;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Statistics extends Model
{

    protected $DOC_PATH; // for puts files

    protected $studentsOfGroup; // Students of group

    protected $studentOfModule;

    protected $dataGradesOfStudentsGroups; // for find all groups

    protected $dataOfFile; // each module

    protected $dataEachOfFile; // select module

    protected $speciality; // get from cache speciality

    protected $department; // get from cache department

    protected $idFileGrade = 0;

    protected $shablons = [];

    private $sumGrades = [];

    private $countOfAll2 = [];

    private $conver = [];

    private $EDUBASISID = [];

    public function __construct($idFileGrade)
    {
        $this->conver = Config::get('grade-proportional');
        $this->dataOfFile = GradesFiles::where('file_info_id', $idFileGrade)->get();
        $this->EDUBASISID = Students::getSumContractOrButjetStudent(Grades::select('id_student')->where('grade_file_id', $this->dataOfFile[0]->id)->get()->toArray());
        /**
         * get data from bd about module (generals data for each docs)
         */
        $this->speciality = CacheSpeciality::getSpeciality($this->dataOfFile[0]->SpecialityId)->name;
        $this->department = CacheDepartment::getDepartment($this->dataOfFile[0]->DepartmentId)->name;
    }

    /**
     * @return array
     */
    public function formGeneralStat()
    {
        $table = '';
        $i = 1;
        foreach ($this->dataOfFile as $this->dataEachOfFile) {
            $this->studentOfModule = Grades::where('grade_file_id', $this->dataEachOfFile->id)->get();
            $this->sumGrades = $this->getSumGradesFromEachStudent();
            $table .= '<tr><td>' . $i . '</td><td>' . $this->findSemester() . '</td>';
            $table .= '<td>' . $this->dataEachOfFile->NameDiscipline . ' - ' . $this->dataEachOfFile->ModuleNum . '. ' . $this->dataEachOfFile->NameModule . '</td>';
            $table .= '<td>' . count($this->studentOfModule) . '</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['2'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['2'] / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.($this->sumGrades['gradeOfFiveTypes']['stat']['B']['3']+$this->sumGrades['gradeOfFiveTypes']['stat']['C']['3']).' ('.number_format(($this->sumGrades['gradeOfFiveTypes']['stat']['C']['3']+$this->sumGrades['gradeOfFiveTypes']['stat']['B']['3']) / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.($this->sumGrades['gradeOfFiveTypes']['stat']['B']['4']+$this->sumGrades['gradeOfFiveTypes']['stat']['C']['4']).' ('.number_format(($this->sumGrades['gradeOfFiveTypes']['stat']['C']['4']+$this->sumGrades['gradeOfFiveTypes']['stat']['B']['4']) / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.($this->sumGrades['gradeOfFiveTypes']['stat']['B']['5']+$this->sumGrades['gradeOfFiveTypes']['stat']['C']['5']).' ('.number_format(($this->sumGrades['gradeOfFiveTypes']['stat']['C']['5']+$this->sumGrades['gradeOfFiveTypes']['stat']['B']['5']) / count($this->studentOfModule)*100, 2).'%)</td>
                    ';
            $table .= '<td>' . number_format($this->sumGrades['examGrade'] / count($this->studentOfModule), 2) . '</td>';
            $table .= '<td>' . number_format($this->sumGrades['grade'] / count($this->studentOfModule), 2) . '</td>';
            $table .= '</td><td></td><td></td><td></tr>';
            $i++;
        }

        $this->shablons['body'] = '';
        $this->shablons['title'] = trans("admin/modules/stat.gStat");
        $this->shablons['body'] .= $this->formHeader();
        $this->shablons['body'] .= '<tr><td>№</td><td>Курс</td><td> Назва дисципліни</td><td>Загальна кількість студентів</td><td>Кількість студентів , що склали модуль на \'незадовіль-но\' (відсоток)';
        $this->shablons['body'] .= '</td><td>Кількість студентів , що склали модуль на \'задовільно\' (відсоток)</td><td>Кількість студентів , що склали модуль на \'добре\' (відсоток)</td><td>Кількість студентів , що склали модуль на \'відмінно\' (відсоток)';
        $this->shablons['body'] .= '</td><td>Cередній бал </td> <td>Середній бал поточної успішності</td><td>Важкі</td><td>Легкі</td><td>Середній показник</td></tr>';

        $this->shablons['body'] .= $table;
        $this->shablons['body'] .= $this->formFooter();
        return $this->shablons;
    }

    /**
     * @return array
     */
    public function formGeneralBKStat()
    {
        $table = '';
        $i = 1;
        foreach ($this->dataOfFile as $this->dataEachOfFile) {
            $this->studentOfModule = Grades::where('grade_file_id', $this->dataEachOfFile->id)->get();
            $this->sumGrades = $this->getSumGradesFromEachStudent();
            $table .= '<tr><td>' . $i . '</td><td>' . $this->findSemester() . '</td>';
            $table .= '<td>' . $this->dataEachOfFile->NameDiscipline . ' - ' . $this->dataEachOfFile->ModuleNum . '. ' . $this->dataEachOfFile->NameModule . '</td>';
            $table .= '<td>' . count($this->studentOfModule) . '</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['C']['2'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['C']['2'] / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['B']['2'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['B']['2'] / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['C']['3'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['C']['3'] / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['B']['3'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['B']['3'] / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['C']['4'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['C']['4'] / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['B']['4'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['B']['4'] / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['C']['5'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['C']['5'] / count($this->studentOfModule)*100, 2).'%)</td>
                    <td>'.$this->sumGrades['gradeOfFiveTypes']['stat']['B']['5'].' ('.number_format($this->sumGrades['gradeOfFiveTypes']['stat']['B']['5'] / count($this->studentOfModule)*100, 2).'%)</td>
                    ';
            $table .= '<td>' . number_format($this->sumGrades['examGrade'] / count($this->studentOfModule), 2) . '</td>';
            $table .= '<td>' . number_format($this->sumGrades['grade'] / count($this->studentOfModule), 2) . '</td>';
            $table .= '</td><td></td><td></td><td></tr>';
            $i++;
        }
        $this->shablons['body'] = '';
        $this->shablons['title'] = trans("admin/modules/stat.gBCStat");
        $this->shablons['body'] .= $this->formHeader('Кількість контрактних студентів: ' . $this->EDUBASISID["C"] . '<br>Кількість державних студентів: ' . $this->EDUBASISID["B"]);
        $this->shablons['body'] .= '<tr><td>№</td><td>Курс</td><td> Назва модулю (дисципліни)</td><td>Загальна кількість студентів</td><td>Кількість контрактних студентів , що склали модуль на \'незадовіль-но\' (відсоток)</td><td>Кількість державних студентів , що склали модуль на \'незадовіль-но\' (відсоток)';
        $this->shablons['body'] .= '</td><td>Кількість контрактних студентів , що склали модуль на \'задовільно\' (відсоток)</td><td>Кількість державних студентів , що склали модуль на \'задовільно\' (відсоток)</td><td>Кількість контрактних студентів , що склали модуль на \'добре\' (відсоток)</td><td>Кількість державних студентів , що склали модуль на \'добре\' (відсоток)</td><td>Кількість контрактних студентів , що склали модуль на \'відмінно\' (відсоток)</td><td>Кількість державних студентів , що склали модуль на \'відмінно\' (відсоток)';
        $this->shablons['body'] .= '</td><td>Cередній бал </td> <td>Середній бал поточної успішності</td><td>Важкі</td><td>Легкі</td><td>Середній показник</td></tr>';
        $this->shablons['body'] .= $table;
        $this->shablons['body'] .= $this->formFooter();
        return $this->shablons;
    }

    public function formDetailedStat()
    {
        $this->shablons['body'] = '';
        $this->shablons['title'] = trans("admin/modules/stat.detailStat");
        $this->shablons['body'] .= $this->formHeader();
        $this->shablons['body'] .= '<tr><td>Група</td><td>П.І.Б</td>';
        foreach ($this->dataOfFile as $this->dataEachOfFile) {
            $this->studentOfModule = Grades::where('grade_file_id', $this->dataEachOfFile->id)->get()->sortBy('group');
                foreach ($this->studentOfModule as $student) {
                    $studentForForm[$student->group][$student->id_student][$this->dataEachOfFile->id]['grade'] = $student->grade;
                    $studentForForm[$student->group][$student->id_student][$this->dataEachOfFile->id]['examGrade'] = $student->exam_grade;
                    $studentForForm[$student->group][$student->id_student]['fio'] = $student->fio;
                }
            $this->shablons['body'] .= '<td>'.$this->dataEachOfFile->NameDiscipline.' - ('.$this->dataEachOfFile->ModuleNum.'.'.$this->dataEachOfFile->NameModule.')';
            $this->shablons['body'] .= '<table width="100%"><tr><td width="50%"><b>Grade</b></td><td><b>Exam Grade</b></td></tr></table></td>';
        }

        $this->shablons['body'] .= '</tr>';
            foreach ($studentForForm as $keyGroup=>$group) {
                foreach ($group as $students) {
                    $this->shablons['body'] .= '<tr><td>'.$keyGroup.'</td><td>'.$students['fio'].'</td>';
                    foreach($students as $studentgrade){
                        if (is_array($studentgrade)){
                            $this->shablons['body'] .= '<td><table width="100%"><tr><td width="50%">'.$studentgrade['grade'].'</td><td>'.$studentgrade['examGrade'].'</td></tr></table> </td>';
                        }

                    }
                    $this->shablons['body'] .= '</tr>';
                }
            }
        $this->shablons['body'] .= $this->formFooter();
        return $this->shablons;
    }

    public function formHeader($beforeTable = '')
    {
        $text = '';
        $text .= '<br /><p align=center>
        '. $this->department.'
        , курс - '.$this->findSemester().'
        ,'.(($this->sumGrades['gradeOfFiveTypes']['type']=='exam')?' Іспит ':' диф.залік ').'
        ,'. date('d.m.Y') .'
        </p><br />
        '.$beforeTable.'<br /><table class="table table-hover " border="1">';
        return $text;
    }

    public function formFooter()
    {
        $text = '';
        $text .= '</table>Загальні дані <br> Не склало – '.count($this->countOfAll2).' ('.number_format(count($this->countOfAll2) / count($this->studentOfModule)*100, 2).'%)';

        return $text;
    }

    /**
     * Convert semester to course
     * @return int
     */
    private function findSemester()
    {
        return ($this->dataEachOfFile->Semester & 1) ? ($this->dataEachOfFile->Semester + 1) / 2 : $this->dataEachOfFile->Semester / 2;
    }

    private function getSumGradesFromEachStudent()
    {

        $sum['examGrade'] = Grades::select('exam_grade')->where('grade_file_id', $this->dataEachOfFile->id)->sum('exam_grade');
        $sum['grade'] = Grades::select('grade')->where('grade_file_id', $this->dataEachOfFile->id)->sum('grade');
        $sum['gradeOfFiveTypes'] = $this->convertGrades();
        return $sum;
    }

    private function convertGrades(){
        $qty = ($this->dataEachOfFile->qty_questions)?$this->dataEachOfFile->qty_questions:24; /*small bag fix because , because , because )))) ahahaha*/
        $type = ($this->dataEachOfFile->type_exam_id==2)?'exam':($this->dataEachOfFile->type_exam_id==1)?(AllowedDiscipline::where('arrayAllowed', 'like', '%'.$this->dataEachOfFile->DisciplineVariantID.'%')->get()->first())?'exam':'dz':'dz';
        $fromConfigArray = $this->conver[$type][$qty];

        $data = ['stat'=>[
            'B'=>['2'=>0,'3'=>0, '4'=>0, '5'=>0],
            'C'=>['2'=>0,'3'=>0, '4'=>0, '5'=>0],
            '2'=>0],
            'type'=>$type];

        foreach ($this->studentOfModule as $student) {
            if($student->exam_grade==0) {
                $data['stat'][Students::getStudentEDUBASISID($student->id_student)]['2']++;
                $data['stat']['2']++;
                $this->countOfAll2[$student->id_student]=true;
            }
            foreach($fromConfigArray as $keyGrade=>$convert){
                if($convert['from']<=$student->exam_grade && $convert['to']>=$student->exam_grade){
                    $data['stat'][Students::getStudentEDUBASISID($student->id_student)][$keyGrade]++;
                }if($student->exam_grade==0){
                }
            }

        }
        
        return $data;
    }

}
