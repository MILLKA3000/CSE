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
    <div class="pull-right">
        <div class="pull-right">
            <a href="/documents/{{$about_module->DepartmentId}}/{{$about_module->ModuleVariantID}}/true/getAllConsultingDocuments" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil"></span>  {!! trans("admin/modules/consulting.getDocsWithGrades") !!}</a>

        </div>
    </div>
    <div class="block">
        <h3>
            {!! trans("admin/modules/arhive.nameDiscipline") !!}: {{$about_module->NameDiscipline}} <br>
            {!! trans("admin/modules/arhive.nameModule") !!}: {{$about_module->ModuleNum}}. {{$about_module->NameModule}}
        </h3>
    </div>
    <input name="_token" id="_token" type="hidden" value="{{  csrf_token() }}">
    <table id="table2" class="table table-striped table-hover ui-datatable"
           data-paging="true"
           data-info="true"
           data-length-change="true"
           >
        <thead>
        <tr>
            <th data-sortable="true" data-filterable="select">
                {!! trans("admin/modules/consulting.studentGroup") !!}
            </th>
            <th data-sortable="true" data-filterable="text">
                {!! trans("admin/modules/consulting.studentName") !!}
            </th>
            <th>
                {!! trans("admin/modules/consulting.grade") !!}
            </th>
            <th>
                {!! trans("admin/modules/consulting.consultGrade") !!}
            </th>
        </tr>
        </thead>
        @foreach($students as $student)
            <tr id="tr{{$student->id_student}}">
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
                    <div class="block">
                        @if(!in_array(Auth::user()->role_id,[8,2,4]))
                             <div class="form-group">
                                <div class="controls">
                                    {!! Form::select('role_id', ['0'=>'0(не склав)','12'=>12,'13'=>13,'14'=>14,'15'=>15,'16'=>16,'18'=>18,'20'=>20], (isset($student->grade_consulting))?$student->grade_consulting:'',array('id'=>"i$student->id_student",'class' => 'form-control')) !!}
                                </div>
                            </div>
                            <a href="#!inline" data-student-id="{{$student->id_student}}" id="{{$student->id_student}}" class="add btn  btn-success left">{!! trans("admin/modules/consulting.addGrade") !!}</a>
                            <a href="#!inline" data-student-id="{{$student->id_student}}" id="{{$student->id_student}}" class="clear btn  btn-success left">{!! trans("admin/modules/consulting.clearGrade") !!}</a>
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
    <script src="{{ asset('js/dataTablesSelect.js') }}"></script>
    <script>
        $('#table2').dataTableHelper();
            $('.add').on('click', function () {
                var self = this;
                $.post("/teacher/saveGrade", {
                    'modnum':{{$about_module->ModuleVariantID}},
                    'depId':{{$about_module->DepartmentId}},
                    '_token': $("#_token").val(),
                    'student': $(this).attr('id'),
                    'value': $("#i" + $(this).attr('id')).val()
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.status == 'true') {
                        $('#tr' + $(self).data('student-id')).css({'backgroundColor': '#C8FFC8', 'color': 'black'});
                    } else if (data.status == 'false') {
                        alert(data.message);
                        $('#i' + $(self).data('student-id')).val(data.grade);
                        $('#tr' + $(self).data('student-id')).css({'backgroundColor': '#FFE3C8', 'color': 'black'});
                    }
                })
            })

            $('.clear').on('click', function () {
                var self = this;
                $.post("/teacher/clearGrade", {
                    'modnum':{{$about_module->ModuleVariantID}},
                    'depId':{{$about_module->DepartmentId}},
                    '_token': $("#_token").val(),
                    'student': $(this).attr('id'),
                    'value': $("#i" + $(this).attr('id')).val()
                }).done(function (data) {
                    data = JSON.parse(data);
                    $('#i' + $(self).data('student-id')).val('');
                })
            });
        $('.pagination li a, select, input').on('click', function () {
            $("body").delegate(".add", "click", function(e) {
                var self = this;
                $.post("/teacher/saveGrade", {
                    'modnum':{{$about_module->ModuleVariantID}},
                    'depId':{{$about_module->DepartmentId}},
                    '_token': $("#_token").val(),
                    'student': $(this).attr('id'),
                    'value': $("#i" + $(this).attr('id')).val()
                }).done(function (data) {
                    data = JSON.parse(data);
                    if (data.status == 'true') {
                        $('#tr' + $(self).data('student-id')).css({'backgroundColor': '#C8FFC8', 'color': 'black'});
                    } else if (data.status == 'false') {
                        alert(data.message);
                        $('#i' + $(self).data('student-id')).val(data.grade);
                        $('#tr' + $(self).data('student-id')).css({'backgroundColor': '#FFE3C8', 'color': 'black'});
                    }
                })
            });
            $("body").delegate(".clear", "click", function(e) {
                var self = this;
                $.post("/teacher/clearGrade", {
                    'modnum':{{$about_module->ModuleVariantID}},
                    'depId':{{$about_module->DepartmentId}},
                    '_token': $("#_token").val(),
                    'student': $(this).attr('id'),
                    'value': $("#i" + $(this).attr('id')).val()
                }).done(function (data) {
                    data = JSON.parse(data);
                    $('#i' + $(self).data('student-id')).val('');
                })
            });
        });
    </script>
@stop
