@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/Excel.loadXLStitle") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/Excel.loadXLStitle") !!}
            <div class="pull-right">
                <div class="pull-right">

                </div>
            </div>
        </h3>
    </div>
    <div class="row ">

        <div class="panel panel-info">
            <div class="panel-heading" style="color:#000000">
                {!! trans("admin/modules/Excel.Change_XLS_file") !!}
            </div>
            <div class="panel-body">
                @if(Session::has('success'))
                    <div class="alert-box success">
                        <h2>{!! Session::get('success') !!}</h2>
                    </div>
                @endif
                {!! Form::open(array('url'=>'excel/importXLS','method'=>'post', 'files'=>true)) !!}
                <div class="control-group">
                    <div class="controls">
                        {!! Form::file('xls') !!}
                        <p class="errors">{!!$errors->first('xls')!!}</p>
                        @if(isset($error))
                            <p class="text-danger">{!! $error !!}</p>
                        @endif
                    </div>
                </div>
                <div id="success"> </div>
                {!! Form::submit('Upload', array('class'=>'btn btn-success btn-sm cboxElement')) !!}
                {!! Form::close() !!}
            </div>

        </div>

        <div class="about-section">
            <div class="text-content">
                <div class="span7 offset1">
                </div>
            </div>
        </div>
    </div>

@stop

{{-- Scripts --}}
@section('scripts')
    <script>

        $('#tablet').DataTable();

    </script>
@stop
