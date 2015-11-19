<?php

namespace App\Model\CreateDocuments;

use App\CacheDepartment;
use App\CacheSpeciality;
use App\GradesFiles;
use App\Model\Contingent\Students;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Database\Eloquent\Model;
use App\Grades;
use Illuminate\Support\Facades\DB;

class Statistics extends Model
{

    protected $DOC_PATH; // for puts files

    protected $studentsOfGroup; // Students of group

    protected $dataGradesOfStudentsGroups; // for find all groups

    protected $dataOfFile; // each module

    protected $dataEachOfFile; // select module

    protected $speciality; // get from cache speciality

    protected $department; // get from cache department

    protected $idFileGrade = 0;

    protected $shablons = [];

    private $EDUBASISID = [];

    public function __construct($idFileGrade)
    {
        $this->dataOfFile = GradesFiles::where('file_info_id', $idFileGrade)->get();

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
        $this->shablons['body'] = '';
        $this->shablons['title'] = trans("admin/modules/stat.gStat");
        $this->shablons['body'] .= $this->formHeader();
        $this->shablons['body'] .= '<tr><td>№</td><td>Курс</td><td> Назва модулю (дисципліни)</td><td>Загальна кількість студентів</td><td>Кількість студентів , що склали модуль на \'незадовіль-но\' (відсоток)';
        $this->shablons['body'] .= '</td><td>Кількість студентів , що склали модуль на \'задовільно\' (відсоток)</td><td>Кількість студентів , що склали модуль на \'добре\' (відсоток)</td><td>Кількість студентів , що склали модуль на \'відмінно\' (відсоток)';
        $this->shablons['body'] .= '</td><td>Cередній бал </td> <td>Середній бал поточної успішності</td><td>Важкі</td><td>Легкі</td><td>Середній показник</td></tr>';
        $i = 1;
        foreach ($this->dataOfFile as $this->dataEachOfFile) {
            $studentOfModule = Grades::where('grade_file_id', $this->dataEachOfFile->id)->get();
            $sumGrades = $this->getSumGradesFromEachStudent();
            $this->shablons['body'] .= '<tr><td>' . $i . '</td><td>' . $this->findSemester() . '</td>';
            $this->shablons['body'] .= '<td>' . $this->dataEachOfFile->NameDiscipline . ' - ' . $this->dataEachOfFile->ModuleNum . '. ' . $this->dataEachOfFile->NameModule . '</td>';
            $this->shablons['body'] .= '<td>' . count($studentOfModule) . '</td><td></td><td></td><td></td><td></td>';
            $this->shablons['body'] .= '<td>' . number_format($sumGrades['examGrade'] / count($studentOfModule), 2) . '</td>';
            $this->shablons['body'] .= '<td>' . number_format($sumGrades['grade'] / count($studentOfModule), 2) . '</td>';
            $this->shablons['body'] .= '</td><td></td><td></td><td></tr>';
            $i++;
        }
        $this->shablons['body'] .= $this->formFooter();
        return $this->shablons;
    }

    /**
     * @return array
     */
    public function formGeneralBKStat()
    {
        $this->EDUBASISID = Students::getSumContractOrButjetStudent(Grades::select('id_student')->where('grade_file_id', $this->dataOfFile[0]->id)->get()->toArray());
        $this->shablons['body'] = '';
        $this->shablons['title'] = trans("admin/modules/stat.gBCStat");
        $this->shablons['body'] .= $this->formHeader('Кількість контрактних студентів: ' . $this->EDUBASISID["C"] . '<br>Кількість державних студентів: ' . $this->EDUBASISID["B"]);
        $this->shablons['body'] .= '<tr><td>№</td><td>Курс</td><td> Назва модулю (дисципліни)</td><td>Загальна кількість студентів</td><td>Кількість студентів , що склали модуль на \'незадовіль-но\' (відсоток)';
        $this->shablons['body'] .= '</td><td>Кількість студентів , що склали модуль на \'задовільно\' (відсоток)</td><td>Кількість студентів , що склали модуль на \'добре\' (відсоток)</td><td>Кількість студентів , що склали модуль на \'відмінно\' (відсоток)';
        $this->shablons['body'] .= '</td><td>Cередній бал </td> <td>Середній бал поточної успішності</td><td>Важкі</td><td>Легкі</td><td>Середній показник</td></tr>';
        $i = 1;
        foreach ($this->dataOfFile as $this->dataEachOfFile) {
            $studentOfModule = Grades::where('grade_file_id', $this->dataEachOfFile->id)->get();
            $sumGrades = $this->getSumGradesFromEachStudent();
            $this->shablons['body'] .= '<tr><td>' . $i . '</td><td>' . $this->findSemester() . '</td>';
            $this->shablons['body'] .= '<td>' . $this->dataEachOfFile->NameDiscipline . ' - ' . $this->dataEachOfFile->ModuleNum . '. ' . $this->dataEachOfFile->NameModule . '</td>';
            $this->shablons['body'] .= '<td>' . count($studentOfModule) . '</td><td></td><td></td><td></td><td></td>';
            $this->shablons['body'] .= '<td>' . number_format($sumGrades['examGrade'] / count($studentOfModule), 2) . '</td>';
            $this->shablons['body'] .= '<td>' . number_format($sumGrades['grade'] / count($studentOfModule), 2) . '</td>';
            $this->shablons['body'] .= '</td><td></td><td></td><td></tr>';
            $i++;
        }
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
            $studentOfModule = Grades::where('grade_file_id', $this->dataEachOfFile->id)->get()->sortBy('group');
                foreach ($studentOfModule as $student) {
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
//        dd($studentForForm);
        $this->shablons['body'] .= $this->formFooter();
        return $this->shablons;
    }

    public function formHeader($beforeTable = '')
    {
        $text = '';

        $text .= $beforeTable . '<table class="table table-hover " border="1">';
        return $text;
    }

    public function formFooter()
    {
        $text = '';

        $text .= '</table>';

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
        return $sum;
    }

}
