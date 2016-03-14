
<tr>
    <td width=10% align=center>{{ $student['num'] }}</td>
    <td width=50%>{{ App\Model\Contingent\Students::getStudentFIO($student['id_student']) }}</td>
    <td width=15%>{{ App\Model\Contingent\Students::getStudentBookNum($student['id_student']) }}</td>
    <td>{{$student['grade']}}</td>
    <td>{{$student['exam_grade']}}</td>
    @if(isset($student->checkExam))
        <td width=10%>{{$student['checkExam']}}</td>
    @endif
    <td>{{$student['final_grade']}}</td></tr>
