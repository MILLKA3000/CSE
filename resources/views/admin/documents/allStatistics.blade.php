@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/arhive.title") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <link href="{{ asset('css/datePicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/stat.title") !!}

        </h3>
    </div>
    <?= $data['general']; ?>

    <?= $data['bk']; ?>
@stop

{{-- Scripts --}}
@section('scripts')

@stop
