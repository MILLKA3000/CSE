@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/getExcel.loadXMLtitle") !!} :: @parent
@stop

@section('styles')
    <link href="{{ asset('css/fileinput.min.css') }}" rel="stylesheet">
@endsection

{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {{ trans("admin/modules/getExcel.".$title)}}
            <div class="pull-right">
                <div class="pull-right">

                </div>
            </div>
        </h3>
    </div>
    <div class="row ">

        <div class="panel panel-info">
            <div class="panel-heading" style="color:#000000">
                {{ trans("admin/modules/getExcel.".$title)}}
            </div>
            <div class="panel-body">
                @if(Session::has('success'))
                    <div class="alert-box success">
                        <h2>{!! Session::get('success') !!}</h2>
                    </div>
                @endif
                {!! Form::open(array('url'=>(isset($path))?$path:'excel/loadXML','method'=>'post', 'files'=>true)) !!}

                        <label class="control-label">Select XML or ZIP File</label>
                    <input id="input-2" type="file"
                           class="file btn btn-success btn-sm"
                           multiple="true"
                           name="xml[]"
                           data-show-upload="false"
                           data-show-caption="true"
                           data-allowed-file-extensions='["xml", "zip"]'>
                        {{--{!! Form::file('xml[]', array('multiple'=>true,'array'=>'file','data-show-upload'=>'false','data-show-caption'=>'true')) !!}--}}
                        <p class="errors">{!!$errors->first('xml')!!}</p>
                        @if(isset($error))
                            <p class="text-danger">{!! $error !!}</p>
                        @endif
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

    <script src="{{ asset('js/fileinput.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.btn.btn-file , .btn.fileinput-remove').attr('style','height:35px');
            $('.glyphicon.glyphicon-folder-open, .glyphicon.glyphicon-trash').hide();
        });
//        $('#tablet').DataTable();

    </script>
@stop
