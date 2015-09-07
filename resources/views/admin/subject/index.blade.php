@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/getExcel.title") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/getExcel.title") !!}
            <div class="pull-right">
                <div class="pull-right">

                </div>
            </div>
        </h3>
    </div>
    <div class="form-group">
        {!! Form::label('departments', trans("admin/modules/getExcel.selectDepartment"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::select('departments', $departments->lists('DEPARTMENT', 'DEPARTMENTID'), '',array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('speciality', trans("admin/modules/getExcel.selectSpeciality"), array('class' => 'control-label')) !!}
        <div class="controls">
            {!! Form::select('speciality', $speciality->lists('SPECIALITY', 'SPECIALITYID'), '',array('class' => 'form-control')) !!}
        </div>
    </div>

    {{dd($speciality)}}

    {{--<table id="table" class="table table-striped table-hover">--}}
        {{--<thead>--}}
        {{--<tr>--}}
            {{--<th>{!! trans("admin/subject.SPECIALITY") !!}</th>--}}
            {{--<th>{!! trans("admin/subject.SPECIALITYID") !!}</th>--}}
        {{--</tr>--}}
        {{--</thead>--}}
        {{--<tbody></tbody>--}}
    {{--</table>--}}
@stop

{{-- Scripts --}}
@section('scripts')

@stop
