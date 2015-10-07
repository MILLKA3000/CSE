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
    <div class="form-group col-xs-4">
        Search of Date
        <br>
        <br>
        <div class='input-group date' id='datetimepicker1'>
            <input type='text' class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
        </div>
    </div>
    <table id="table" class="table table-hover ui-datatable"
           data-global-search="true"
           data-ajax="{!! $type !!}/data"
           data-paging="true"
           data-info="true"
           data-length-change="true"
           data-page-length="5">
        <thead>
        <tr>
            <th>{!! trans("admin/modules/arhive.created") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.semester") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.department") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.speciality") !!}</th>
            <th>{!! trans("admin/modules/arhive.nameDiscipline") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.typeExam") !!}</th>
            <th data-sortable="true" data-filterable="select">{!! trans("admin/modules/arhive.user") !!}</th>
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

        $(function () {
            $('#table_filter').hide();
            dp = $('#datetimepicker1').datetimepicker({
                format: 'YYYY-MM-DD',
                defaultDate: 'moment',
            }).on('dp.change', function (e) {
                oTable.search(moment(e.date).format("YYYY-MM-DD")).ajax.reload();
            });
            oTable.search(moment(dp.date).format("YYYY-MM-DD")).ajax.reload();

            $('#table tbody').on('click', 'td', function () {
                var tr = $(this).closest('tr');
                var row = oTable.row( tr );
                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
//                    console.log(row);
                    row.child( addButton(row.data()) ).show();
                    tr.addClass('shown');
                }
            } );

            function addButton ( d ) {
                return  '<a href="/xls/'+d[8]+'/'+d[9]+'" class="btn btn-success btn-sm "> Get original xls </a>'+
                        '&nbsp;&nbsp;'+
                        '<a href="/documents/'+d[7]+'/getAllDocuments" class="btn btn-warning btn-sm "> Get all Documents </a>' +
                        '&nbsp;&nbsp;'+
                        '<a href="/documents/'+d[7]+'/getAllStatistics" class="btn btn-warning btn-sm "> Get all Statistics </a>' +
                        '&nbsp;&nbsp;'+
                        '<a href="/recheck/'+d[7]+'/examGrade" class="btn btn-danger btn-sm "> Recheck Exam Grade </a>';
            };
        });
    </script>
@stop
