@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/logs.title") !!} :: @parent
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
            {!! trans("admin/modules/logs.title") !!}

        </h3>
    </div>
    <table id="table2" class="table table-hover ui-datatable"
           data-global-search="true"
           data-ajax="{!! $type !!}/data"
           data-paging="true"
           data-info="true"
           data-length-change="true"
           data-page-length="50">
        <thead>
        <tr>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/users.name") !!}</th>
            <th data-sortable="true" data-filterable="text">{!! trans("admin/modules/logs.titleAction") !!}</th>
            <th data-sortable="true">{!! trans("admin/modules/logs.date") !!}</th>
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
