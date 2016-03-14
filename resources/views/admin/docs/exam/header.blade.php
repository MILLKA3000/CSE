
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
<span align=left> Факультет <u>{{$this['department']}}</u></span><br>
<span align=left> Спеціальність <u>{{$this['speciality']}}</u></span>
<span align=right>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Група_<u>{{$this['group']}}</u>___</span>
&nbsp;&nbsp;&nbsp;&nbsp;{{$this['dataEachOfFile']->EduYear}}/{{($this['dataEachOfFile']->EduYear + 1)}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Курс _<u>{{$this['semester']}}</u>___<br />
<p align=center>ЕКЗАМЕНАЦІЙНА ВІДОМІСТЬ №____ </p>
<p>З <u>{{$this['dataEachOfFile']->ModuleNum}}. {{$this['dataEachOfFile']->NameDiscipline}}</u> - <u>{{$this['dataEachOfFile']->NameModule}}</u></p>
<p>За _<u>{{$this['dataEachOfFile']->Semester}}</u>___ навчальний семестр, екзамен <u>_{{((Session::has('date')) ? Session::get('date') : $this['date'])}}___</u></p>
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
        