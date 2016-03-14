<tr>
    <td width=10% align=center>{{ $student['num'] }}</td>
    <td width=50%>{{ App\Model\Contingent\Students::getStudentFIO($student['id_student']) }}</td>
    <td width=15%>{{ App\Model\Contingent\Students::getStudentBookNum($student['id_student']) }}</td>
    <td width=10%>{{ $student['grade_consulting'] }}</td>
    <td></td>
</tr>

