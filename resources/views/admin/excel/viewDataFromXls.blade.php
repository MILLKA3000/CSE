@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/Excel.loadXLStitle") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <link href="{{ asset('css/datePicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/Excel.loadXLStitle") !!}
            <div class="pull-right">
                <div class="pull-right">

                </div>
            </div>
        </h3>
    </div>
    @if(isset($message['success']))
        <div class="alert alert-success">
            {!! $message['success'][0] !!}
        </div>
    @elseif(isset($message['error']))
        <div class="alert alert-danger">
            <h4>System has next problems. Please recheck them</h4>
            <ul>
            @foreach($message['error'] as $err)
                <li>{!! $err !!}</li>
            @endforeach
            </ul>
        </div>
    @endif

    @if(isset($message['success']))
        <div class="form-group col-xs-4">
            <div class='input-group date' id='datetimepicker1'>
                <input type='text' class="form-control" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
            </div>
        </div>
        <div class="form-group col-xs-4">
            <a href="/documents/{{$id_file}}/getAllDocuments" id="vid" class="btn btn-warning btn-sm "> Get all Documents </a>
            <a href="/documents/{{$id_file}}/getAllStatistics" id="stat" class="btn btn-warning btn-sm "> Get all Statistics </a>
        </div>
    @endif

    <div class="row ">
        <div class="col-xs-12">
            <h3 class="text-center">{!! trans("admin/modules/Excel.dataInFile") !!}</h3>

            <div class="panel-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <?php $i=1 ?>
                    @foreach($data->get() as $item)
                        <li class=""><a href="#tab{{$i++}}" data-toggle="tab" aria-expanded="true">{{$item->getTitle()}}</a></li>
                    @endforeach
                </ul>
                <div class="tab-content">


                    <?php $i=1 ?>
                    @foreach($data->get() as $item)
                        <div class="tab-pane" id="tab{{$i++}}">
                            <br/>

                            <table id="tablet{{$i}}" class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    @foreach ($item as $k=>$it)
                                        @foreach ($it as $key=>$col)
                                            <th>{{$key}}</th>
                                        @endforeach
                                        <?php break; ?>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($item as $k=>$it)
                                    <tr>
                                    @foreach ($it as $key=>$col)

                                            <td>
                                                {{$it[$key]}}
                                            </td>

                                    @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach

                </div>
                <!-- Tab panes -->
                </div>
            </div>
    </div>
@stop

{{-- Scripts --}}
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="{{ asset('js/datePicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script>
        $(function () {
            dp = $('#datetimepicker1').datetimepicker({
                format: 'DD.MM.YYYY',
                defaultDate: 'moment'
            }).on('dp.change', function (e) {
                $.get("/settings/toSessionDate", {
                    'date':moment(e.date).format("DD.MM.YYYY")
                });
            });
        });
        $('#tablet2').DataTable();
        $('#tablet3').DataTable();
        $('#tablet4').DataTable();
    </script>
@stop