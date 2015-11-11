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
            <a href="/documents/{{$about_module->ModuleVariantID}}/true/getAllConsultingDocuments" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-pencil"></span>  Get Docs with grades</a>

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
           data-page-length="15">
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
                        @if(!in_array(Auth::user()->role_id,[8]))
                            <input type="text" class="put form-control col-xs-6" style="width:50px;margin-right: 10px;" id="i{{$student->id_student}}" value="{{$student->grade_consulting}}">
                            <a href="#!inline" data-student-id="{{$student->id_student}}" id="{{$student->id_student}}" class="add btn  btn-success left">Add</a>
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
        $('.add').on('click',function(){
            var self = this;
            $.post( "/teacher/saveGrade", {'modnum':{{$about_module->ModuleVariantID}},'_token':$("#_token").val(),'student':$(this).attr('id'),'value':$("#i"+$(this).attr('id')).val()}).done(function(data){
                if(data=='true'){
                    $('#tr'+$(self).data('student-id')).css({'backgroundColor': '#C8FFC8', 'color': 'black'});
                }else if(data=='false'){
                    $('#tr'+$(self).data('student-id')).css({'backgroundColor': '#FFE3C8', 'color': 'black'});
                }
            })
        })
    </script>
@stop
