@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/recheckGredes.title") !!} :: @parent
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
    <input name="_token" id="_token" type="hidden" value="{{  csrf_token() }}">
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/recheckGredes.title") !!}
            <div class="pull-right">
                <div class="pull-right">

                </div>
            </div>
        </h3>
    </div>
    <ul class="nav nav-tabs">
        @foreach($about_module as $item)
            <li><a href="#tab{{$item->id}}" data-toggle="tab" aria-expanded="true">{{$item->ModuleNum}}. {{$item->NameModule}}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach($about_module as $item)
            <div class="tab-pane" id="tab{{$item->id}}">
                <br/>
                <div class="row ">
                    <div class="col-xs-12">
                        <div class="panel-body">
                            <table id="tablet{{$item->id}}" class="table table-striped table-hover ui-datatable"
                                   data-paging="true"
                                   data-info="true"
                                   data-length-change="true"
                                   data-page-length="15">
                                <thead>
                                <tr>
                                    <th data-sortable="true" data-filterable="select">{!! trans("admin/student.group") !!}</th>
                                    <th data-sortable="true" data-filterable="text">{!! trans("admin/student.fio") !!}</th>
                                    <th data-sortable="true" data-filterable="text">{!! trans("admin/student.code") !!}</th>
                                    <th>{!! trans("admin/student.grade") !!}</th>
                                    <th>{!! trans("admin/student.credits_cur") !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($item->students as $student)
                                        <tr id="tr{{$student->id}}">
                                            <td>{{$student->group}}</td>
                                            <td>{{$student->fio}}</td>
                                            <td>{{$student->code}}</td>
                                            <td>{{$student->grade}}</td>
                                            <td>
                                                <input type="text" value="{{$student->exam_grade}}"  style="width:50px;margin-right: 10px;" id="i{{$student->id}}" class="form-control input-sm" />
                                                <a href="#!inline" data-student-id="{{$student->id}}" data-module="{{$item->id}}" class="add btn  btn-success left">Recheck</a>
                                            </td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop

{{-- Scripts --}}
@section('scripts')
    <script src="{{ asset('js/dataTablesSelect.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.nav.nav-tabs li:first-child').addClass('active');
            $('.tab-pane:first-child').addClass('active');
            $.each($('.table'),function(){
                $(this).dataTableHelper();
            });

            $('.add').on('click',function(){
                var self = this;
                $.post( "/recheck/saveGrade", {'id':$(this).data('student-id'),'_token':$("#_token").val(),'value':$("#i"+$(this).data('student-id')).val()}).done(function(data){
                   if(data=='true')
                    {
                        $('#tr'+$(self).data('student-id')).css({'backgroundColor': '#C8FFC8', 'color': 'black'});
                    }else if(data=='false'){
                       $('#tr'+$(self).data('student-id')).css({'backgroundColor': '#FFE3C8', 'color': 'black'});
                   }
                });
            })
        })

    </script>
@stop
