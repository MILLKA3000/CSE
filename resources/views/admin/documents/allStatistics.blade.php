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

    <div class="panel-group" id="accordion">

        @foreach($data as $key=>$d)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title row">
                    <div class="col-xs-10">
                        <a data-toggle="collapse" data-parent="#accordion" class="btn btn-sm btn-danger" href="#{{$key}}">
                           View - {{$d['title']}}
                        </a>
                    </div>
                    <div class="col-xs-2">
                        <a data-toggle="collapse" data-parent="#accordion" class="stat btn btn-sm btn-success right" href="#inline" key="{{$key}}">
                            {!! trans("admin/modules/stat.download") !!}
                        </a>
                    </div>
                </h4>
            </div>
            <div id="{{$key}}" class="panel-collapse collapse">
                <div class="panel-body">
                    <?= $d['body']?>
                </div>
            </div>
        </div>
        @endforeach
    </div>

@stop

{{-- Scripts --}}
@section('scripts')
    <script>
        $('.stat').on('click',function(){
            $.get( "/documents/download/"+$(this).attr('key')+"/{{$idFileGrade}}", function( data ) {
                document.location = data;
            });
        })
    </script>
@stop
