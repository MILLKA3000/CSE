
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
        <td width=80%> Факультет <u>{{ $this['department'] }}</u></td><td>Група_<u>{{ $this['group'] }}</u>___</td>
    </tr>
    <tr>
        <td width=80%> <u>{{ $this['dataEachOfFile']->first()->EduYear }} / {{ ($this['dataEachOfFile']->first()->EduYear + 1)}}</u> навчальний рік</td><td>Курс _<u>{{ $this['semester'] }}</u>___</td>
    </tr>
    <tr>
        <td width=80%>  Спеціальність <u>{{ $this['speciality'] }}</u></td><td></td>
    </tr>
</table>
<p align=center> ЕКЗАМЕНАЦІЙНА ВІДОМІСТЬ УСНОЇ СПІВБЕСІДИ №__________ </p>
<p>З <u>{{ $this['dataEachOfFile']->first()->ModuleNum}}. {{ $this['dataEachOfFile']->first()->NameDiscipline}}</u> - <u>{{ $this['dataEachOfFile']->first()->NameModule}}</u></p>
<table class=guestbook width=625 align=center cellspacing=0 cellpadding=3 border=0>
    <tr>
        <td width=30%>За _<u>{{ $this['dataEachOfFile']->first()->Semester}}</u>___ навчальний семестр,</td><td width=20%><u>_{{$this['date']}}___</u></td><td width=50%></td>
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
