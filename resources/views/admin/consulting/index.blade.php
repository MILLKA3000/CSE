@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/consulting.title") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/consulting.title") !!}

        </h3>
    </div>
    <table id="table" class="table table-responsive table-hover">
        <thead>
        <tr>
            <th>{!! trans("admin/modules/arhive.EduYear") !!}<input type="EduYear" class="global_filter" id="global_filter"></th>
            <th>{!! trans("admin/modules/arhive.semester") !!}</th>
            <th>{!! trans("admin/modules/arhive.department") !!}</th>
            <th>{!! trans("admin/modules/arhive.speciality") !!}</th>
            <th>{!! trans("admin/modules/arhive.nameDiscipline") !!}</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')
    <script src="{{ asset('js/dataTablesSelect.js') }}"></script>
    <script>
        $(document).ready(function () {
//            $('#table thead th').each(function () {
//                var title = $('#table thead th').eq($(this).index()).text();
//                $(this).html('<input type="text" placeholder="" class="form-control" style="max-width: 50%"/>');
//            });
//
//
//            // Apply the search
//            oTable.columns().every(function () {
//                var that = this;
//
//                $('input', this.footer()).on('keyup change', function () {
//                    if (that.search() !== this.value) {
//                        that.search(this.value).draw();
//                    }
//                });
//            });
        });
    </script>
@stop
