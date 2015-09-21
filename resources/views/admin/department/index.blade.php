@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/department.title") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/department.list") !!}
            <div class="pull-right">
                <div class="pull-right">
                    <a href="{!! URL::to('department/create') !!}"
                       class="btn btn-sm  btn-primary"><span
                                class="glyphicon glyphicon-plus-sign"></span> {{
					trans("admin/modal.new") }}</a>
                </div>
            </div>
        </h3>
    </div>

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th>{!! trans("admin/department.name") !!}</th>
            <th>{!! trans("admin/department.active") !!}</th>
            <th>{!! trans("admin/department.user") !!}</th>
            <th>{!! trans("admin/department.action") !!}</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
@stop

{{-- Scripts --}}
@section('scripts')

@stop
