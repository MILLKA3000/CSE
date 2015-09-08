@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/getExcel.loadXMLtitle") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/getExcel.loadXMLtitle") !!}
            <div class="pull-right">
                <div class="pull-right">

                </div>
            </div>
        </h3>
    </div>
    <div class="row ">

        <div class="panel panel-info">
            <div class="panel-heading" style="color:#000000">
                {!! trans("admin/modules/getExcel.Change_XML_file") !!}
            </div>
            <div class="panel-body">
                @if(Session::has('success'))
                    <div class="alert-box success">
                        <h2>{!! Session::get('success') !!}</h2>
                    </div>
                @endif
                {!! Form::open(array('url'=>'excel/loadXML','method'=>'post', 'files'=>true)) !!}
                <div class="control-group">
                    <div class="controls">
                        {!! Form::file('xml') !!}
                        <p class="errors">{!!$errors->first('xml')!!}</p>
                        @if(Session::has('error'))
                            <p class="errors">{!! Session::get('error') !!}</p>
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
