{{--@section('content')--}}
	<h2>{{$data[0]['name']}}</h2>

	<div>
        @foreach($data as $student)
            <b>Предмет:</b> {{$student['discipline']}}<br/>
            <b>Модуль:</b> {{$student['module']}}<br/>
            <b>Поточна успішність:</b> {{$student['grade']}}<br/>
            <b>Оцінка за екзамен:</b> {{$student['examGrade']}}<br/>
            <b>Оцінка за співбесіду:</b> {{$student['consultGrade']}}<br/>
            <br/>
        @endforeach
	</div>
{{--@endsection--}}
