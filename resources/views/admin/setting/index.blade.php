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
            Settings
        </h3>
    </div>
    <?=(Session::get('success'))?'<div class="alert alert-success">'.Session::get('success').'</div>':''?>
    <div class="row">
        {!! Form::open(array('url' => URL::to('settings'), 'method' => 'post', 'files'=> true)) !!}
            {!! Form::label('timeCache', "Cache time", array('class' => 'control-label col-md-2')) !!}
            <div class="controls col-md-4">
                {!! Form::text('timeCache', $data['timeCache'], array('class' => 'form-control')) !!}
            </div>
            <div class="col-md-4">
                <a class="btn btn-primary" href="{{ URL::to('settings/clearCache') }}"><i></i>Clear Cache</a>
            </div>

        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="col-md-4 col-md-offset-4">
            <button type="submit" class="btn btn-sm btn-success sace">
                <span class="glyphicon glyphicon-ok-circle"></span>
                    {{trans("admin/modal.save") }}
            </button>
        </div>
        {!! Form::close() !!}
    </div>

@stop

{{-- Scripts --}}
@section('scripts')
@stop
