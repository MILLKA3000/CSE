@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/consulting.title") !!} :: @parent
@stop
@section('styles')
    <style>
        table.dataTable thead > tr > th {
             padding-left: 0px;
            padding-right: 0px;
        }
    </style>
@stop
{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/consulting.title") !!}

        </h3>
    </div>
    <div class="block">
        <h3>
            {!! trans("admin/modules/arhive.nameDiscipline") !!}: {{$about_module->NameDiscipline}} <br>
            {!! trans("admin/modules/arhive.nameModule") !!}: {{$about_module->ModuleNum}}. {{$about_module->NameModule}}
        </h3>
    </div>
    <input name="_token" id="_token" type="hidden" value="{{  csrf_token() }}">
    <table id="table" class="table">
        <tr>
            <td>
                {!! trans("admin/modules/consulting.studentGroup") !!}
            </td>
            <td>
                {!! trans("admin/modules/consulting.studentName") !!}
            </td>
            <td>
                {!! trans("admin/modules/consulting.grade") !!}
            </td>
            <td>
                {!! trans("admin/modules/consulting.examGrade") !!}
            </td>
            <td>
                {!! trans("admin/modules/consulting.consultGrade") !!}
            </td>
        </tr>
        @foreach($students as $student)
            <tr>
                <td>
                    {{$student->group}}
                </td>
                <td>
                    {{$student->fio}}
                </td>
                <td>
                    {{$student->grade}}
                </td>
                <td>
                    {{$student->exam_grade}}
                </td>
                <td>
                    <div class="block">
                        @if(!in_array(Auth::user()->role_id,[8]))
                            <input type="text" class="put form-control col-xs-6" style="width:50px;margin-right: 10px;" id="i{{$student->id_student}}" value="{{$student->grade_consulting}}">
                            <a href="#!inline" id="{{$student->id_student}}" class="add btn  btn-success left">Add</a>
                        @else
                            {{($student->grade_consulting)?$student->grade_consulting:0}}
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
@stop

{{-- Scripts --}}
@section('scripts')
    <script>
        $('.add').on('click',function(){
            $.post( "/teacher/saveGrade", {'modnum':{{$about_module->ModuleVariantID}},'_token':$("#_token").val(),'student':$(this).attr('id'),'value':$("#i"+$(this).attr('id')).val()});
        })
    </script>
@stop
