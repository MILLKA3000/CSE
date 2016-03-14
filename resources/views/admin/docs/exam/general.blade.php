<tr>
    <td width=10%>{{ $student['num'] }}</td>
    <td width=50%>{{ $student['fio'] }}</td>
    <td width=15%>{{ (int) App\Model\Contingent\Students::getStudentBookNum($student['id_student'])}}</td>
    <td width=10%>{{ $student['exam_grade'] }}</td>
</tr>