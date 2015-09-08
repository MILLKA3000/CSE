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
    <div class="row ">
        <div class="col-xs-6">
            <h3 class="text-center">{!! trans("admin/modules/getExcel.dataInFile") !!}</h3>

            <table id="tablet" class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{!! trans("admin/student.id") !!}</th>
                    <th>{!! trans("admin/student.fio") !!}</th>
                    <th>{!! trans("admin/student.credits_cur") !!}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data->getContent() as $d)
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
        <div class="col-xs-6">
            <h3 class="text-center">{!! trans("admin/modules/getExcel.detailed") !!}</h3>
            <div class="h4">
                <span class="text-muted">{!! trans("admin/modules/getExcel.name_discipline") !!}</span>: {{$data->getContent()->testlist->discipline}}<br/>
                <span class="text-muted">{!! trans("admin/modules/getExcel.name_moduletheme") !!}</span>: {{$data->getContent()->testlist->modulenum}}. {{$data->getContent()->testlist->moduletheme}}<br/>
                <span class="text-muted">{!! trans("admin/modules/getExcel.count_group") !!}</span>: {{count($data->getContent()->testlist)}}<br/>
                <span class="text-muted">{!! trans("admin/modules/getExcel.count_student") !!}</span>: {{$data->count_student}}<br/>
            </div>
            <a href="{{url('excel/downloadXLS/'.$data->file_name)}}" id="download_XLS" class="btn btn-sm btn-danger">
                <div>{{ trans("admin/modules/getExcel.downloadXls") }}!</div>
            </a>

        </div>
    </div>
@stop

{{-- Scripts --}}
@section('scripts')
    <script>
        $('#tablet').DataTable();
    </script>
@stop
