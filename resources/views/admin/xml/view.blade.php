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
                    <a href="{{url('excel/downloadXLS/'.$files)}}" id="download_XLS" class="btn btn-sm btn-danger">
                        <div>{{ trans("admin/modules/getExcel.downloadXls") }}!</div>
                    </a>
                </div>
            </div>
        </h3>
    </div>
    <ul class="nav nav-tabs">
        <?php $i=0 ?>
        @foreach($data as $item)
            <li class=""><a href="#tab{{$i++}}" data-toggle="tab" aria-expanded="true">{{(count($data)<4)?$item['data']->getContent()->testlist->moduletheme:$i}}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">

        <?php $i=0 ?>
        @foreach($data as $item)
            <div class="tab-pane" id="tab{{$i++}}">
                <br/>
                    <div class="row ">
                        <div class="col-xs-6">
                            <h3 class="text-center">{!! trans("admin/modules/getExcel.dataInFile") !!}</h3>
                            <div class="panel-body">
                                 <table id="tablet{{$i}}" class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th>{!! trans("admin/student.id") !!}</th>
                                            <th>{!! trans("admin/student.fio") !!}</th>
                                            <th>{!! trans("admin/student.credits_cur") !!}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($item['data']->getContent() as $d)
                                            @foreach($d->students->student as $student)
                                                <tr>
                                                    <td>{{$student->id}}</td>
                                                    <td>{{$student->fio}}</td>
                                                    <td>{{$student->credits_cur}}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                        <div class="col-xs-6">
                            <h3 class="text-center">{!! trans("admin/modules/getExcel.detailed") !!}</h3>
                            <div class="h4">
                                <span class="text-muted">{!! trans("admin/modules/getExcel.name_discipline") !!}</span>: {{$item['data']->getContent()->testlist->discipline}}<br/>
                                <span class="text-muted">{!! trans("admin/modules/getExcel.name_moduletheme") !!}</span>: {{$item['data']->getContent()->testlist->modulenum}}. {{$item['data']->getContent()->testlist->moduletheme}}<br/>
                                <span class="text-muted">{!! trans("admin/modules/getExcel.count_group") !!}</span>: {{count($item['data']->getContent()->testlist)}}<br/>
                                <span class="text-muted">{!! trans("admin/modules/getExcel.count_student") !!}</span>: {{$item['student']}}<br/>
                            </div>


                        </div>
                    </div>
            </div>
        @endforeach
    </div>
@stop

{{-- Scripts --}}
@section('scripts')
    <script>
        $.each($('.table'),function(){
            $(this).DataTable();
        })
    </script>
@stop
