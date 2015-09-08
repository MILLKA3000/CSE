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


                    <?php $i=0 ?>
                    @foreach($data->get() as $item)
                        <? $i++ ?>
                        <div class="tab-pane" id="tab{{$i}}">
                            <br/>
                            <table id="tablet{{$i}}" class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>{!! trans("admin/student.id") !!}</th>
                                    <th>{!! trans("admin/student.fio") !!}</th>
                                    <th>{!! trans("admin/student.credits_cur") !!}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($item as $ite)
                                        <tr>
                                            <td>
                                                sss
                                            </td>
                                            <td>
                                                sss
                                            </td>
                                            <td>
                                                sss
                                            </td>

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
    <script>
        $('#tablet1').DataTable();
        $('#tablet2').DataTable();
        $('#tablet3').DataTable();
    </script>
@stop