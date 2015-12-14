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
        table.dataTable tr td:nth-child(9) {
            display: flex;
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
    <table id="table2" class="table table-hover ui-datatable"
           data-global-search="true"
           data-ajax="{!! $type !!}/data"
           data-paging="true"
           data-info="true"
           data-length-change="true"
           data-page-length="25">
        <thead>
        <tr>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.EduYear") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.semester") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.department") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.speciality") !!}</th>
            <th data-sortable="true" data-filterable="text">{!! trans("admin/modules/arhive.nameDiscipline") !!}</th>
            <th data-sortable="true" data-filterable="text">{!! trans("admin/modules/arhive.nameModule") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.typeExam") !!}</th>
            <th data-sortable="true">{!! trans("admin/modules/consulting.percent") !!}</th>
            <th>{!! trans("admin/admin.action") !!}</th>

        </tr>
        </thead>
        <tbody></tbody>

    </table>
@stop

{{-- Scripts --}}
@section('scripts')
    <script src="{{ asset('js/dataTablesSelect.js') }}"></script>
    <script>
        $('#table2').dataTableHelper();
    </script>

@stop
