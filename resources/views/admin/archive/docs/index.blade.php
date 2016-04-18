@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/arhive.title") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <link href="{{ asset('css/datePicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/arhive.title") !!}

        </h3>
    </div>
    <table id="table2" class="table responsive no-wrap table-hover dataTable"
           data-global-search="true"
           data-paging="true"
           data-info="true"
           data-length-change="true"
           data-page-length="25"
            width="100%">
        <thead>
        <tr>
            <th data-sortable="true" data-filterable="select" data-priority="1">{!! trans("admin/modules/arhive.created") !!}</th>
            <th data-sortable="true" data-filterable="select" data-priority="1">{!! trans("admin/modules/arhive.semester") !!}</th>
            <th data-sortable="true" data-filterable="select" data-priority="1">{!! trans("admin/modules/arhive.department") !!}</th>
            <th data-sortable="true" data-filterable="select" data-priority="1">{!! trans("admin/modules/arhive.speciality") !!}</th>
            <th data-sortable="true" data-filterable="text" data-priority="1" style="width: 25%!important;" >{!! trans("admin/modules/arhive.nameDiscipline") !!}</th>
            <th data-sortable="true" data-priority="1">{!! trans("admin/modules/arhive.typeExam") !!}</th>
            <th data-sortable="true" data-priority="1">{!! trans("admin/modules/arhive.user") !!}</th>
            <th data-priority="2" width="150">{!! trans("admin/modules/arhive.groups") !!}</th>
            <th data-priority="2">Actions</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="{{ asset('js/datePicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script src="{{ asset('js/dataTablesSelect.js') }}"></script>
    <script>
        $('#table2').dataTableHelper({
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 }
            ],
            "ajax": "{!! $type !!}/data",

        });
    </script>
@stop
