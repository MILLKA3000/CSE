@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/Excel.loadXLStitle") !!} :: @parent
@stop

@section('styles')
    <link href="{{ asset('css/fileinput.min.css') }}" rel="stylesheet">
@endsection

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
                @if(isset($error))
                    <p class="alert alert-danger">{!! $error !!}</p>
                @endif
                {!! Form::open(array('url'=>'excel/importXLS','method'=>'post', 'files'=>true)) !!}

                    <div class="form-group col-xs-3 {{ $errors->has('qtyQuestions') ? 'has-error' : '' }}">
                        {!! Form::label('qtyQuestions', trans("admin/modules/getExcel.type_exam"), array('class' => 'control-label')) !!}
                        <div class="controls">
                            {!! Form::select('qtyQuestions', ['0'=>'Виберіть зі списку (обовязково)','24'=>'24','48'=>'48'], '',array('id'=>"qtyQuestions",'class' => 'form-control')) !!}
                            <span class="help-block">{{ $errors->first('qtyQuestions', ':message') }}</span>
                        </div>
                    </div>

                <div class="control-group">
                    <div class="controls">
                        <label class="control-label">Select File</label>
                        <input id="input-1a"
                               type="file"
                               class="file btn btn-success btn-sm"
                               multiple="false"
                               name="xls"
                               data-show-upload="false"
                               data-show-preview="false"
                               data-show-caption="true"
                               data-allowed-file-extensions='["xls", "xlsx"]'>
                        <p class="errors">{!!$errors->first('xls')!!}</p>

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
        <script src="{{ asset('js/fileinput.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.btn.btn-file , .btn.fileinput-remove').attr('style','height:35px');
            $('.glyphicon.glyphicon-folder-open, .glyphicon.glyphicon-trash').hide();
        });
        $('#tablet').DataTable();

    </script>
@stop
