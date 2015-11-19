@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! trans("admin/modules/arhive.title") !!} :: @parent
@stop

{{-- Content --}}
@section('main')
    <link href="{{ asset('css/datePicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <div class="page-header">
        <h3>
            {!! trans("admin/modules/arhive.title") !!}

        </h3>
    </div>

    <div class="col-md-6">
        <div class="block"><h3>{!! trans("admin/modules/arhive.detailOfFile") !!}</h3></div>
        <table id="table2" class="table table-hover ui-datatable"
        <tr><td style="width:60%">{!! trans("admin/modules/arhive.department") !!}</td><td>{{$modules[0]->DepartmentId}}</td></tr>
        <tr><td>{!! trans("admin/modules/arhive.speciality") !!}</td><td>{{$modules[0]->SpecialityId}}</td></tr>
        <tr><td>{!! trans("admin/modules/arhive.semester") !!}</td><td>{{$modules[0]->Semester}}</td></tr>
        <tr><td><h4>{!! trans("admin/modules/arhive.nameDiscipline") !!}</h5></td><td></td></tr>
        @foreach($modules as $module)
            <tr><td>
                    {{$module->NameDiscipline}}<br/>
                    {{$module->ModuleNum}}. {{$module->NameModule}}
                </td>
                <td>
                    {!! trans("admin/modules/arhive.Qstudents") !!}: {{$module->quantityStudents}}<br/>
                    {!! trans("admin/modules/arhive.Qgroups") !!}: {{$module->quantityGroups}}<br/>
                </td>
            </tr>
        @endforeach
        </table>
    </div>
    <div class="col-md-6">
        <div class="block"><h3>{!! trans("admin/modules/arhive.actionOfFile") !!}</h3></div>
        <table id="table2" class="table table-hover ui-datatable"
            <tr>
                <td>
                    {!! trans("admin/modules/arhive.titleDownload") !!}.
                </td>
                <td>
                    <a href="/xls/{{$modules->path}}/{{$modules->name}}" class="btn btn-success btn-sm "> {!! trans("admin/modules/arhive.getOriginalFile") !!} </a>
                </td>
            </tr>
            @if(!in_array(Auth::user()->role_id,[5,6,7]))
                <tr>
                    <td>
                        {!! trans("admin/modules/arhive.titleGetDoc") !!}
                    </td>
                    <td>
                        <a href="/documents/{{$modules->file_info_id}}/getAllDocuments" class="btn btn-warning btn-sm "> {!! trans("admin/modules/arhive.getDoc") !!} </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! trans("admin/modules/arhive.titleGenStat") !!}
                    </td>
                    <td>
                        <a href="/documents/{{$modules->file_info_id}}/getAllStatistics" class="btn btn-warning btn-sm "> {!! trans("admin/modules/arhive.getstat") !!}</a>
                    </td>
                </tr>
                @if(!in_array(Auth::user()->role_id,[4,5,6,7,8]))
                    <tr>
                        <td>
                            {!! trans("admin/modules/arhive.titleSendEmail") !!}
                        </td>
                        <td>
                            <a href="/documents/{{$modules->file_info_id}}/sendEmails" class="btn btn-warning btn-sm "> {!! trans("admin/modules/arhive.sendEmail") !!}</a>
                        </td>
                    </tr>
                @endif
            @endif
            @if(!in_array(Auth::user()->role_id,[2,4,5,6,7,8]))
                <tr>
                    <td>
                        {!! trans("admin/modules/arhive.titleChangeGrade") !!}
                    </td>
                    <td>
                        <a href="/recheck/{{$modules->file_info_id}}/examGrade" class="btn btn-danger btn-sm "> {!! trans("admin/modules/arhive.changeGrade") !!}</a>
                    </td>
                </tr>
            @endif
            @if(!in_array(Auth::user()->role_id,[3,4,5,6,7,8]))
                <tr>
                    <td>
                        {!! trans("admin/modules/arhive.titleDelDoc") !!}
                    </td>
                    <td>
                        <a href="/documents/{{$modules->file_info_id}}/remove" class="btn btn-primary btn-sm"> {!! trans("admin/modules/arhive.deldoc") !!} </a>
                    </td>
                </tr>
            @endif
        </table>
    </div>
@stop

{{-- Scripts --}}
@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="{{ asset('js/datePicker/bootstrap-datetimepicker.min.js') }}"></script>

    <script src="{{ asset('js/dataTablesSelect.js') }}"></script>
@stop
