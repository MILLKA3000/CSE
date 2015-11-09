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
        <div class="block"><h3>Detailed data of file</h3></div>
        <table id="table2" class="table table-hover ui-datatable"
        <tr><td style="width:60%">Department</td><td>{{$modules[0]->DepartmentId}}</td></tr>
        <tr><td>Speciality</td><td>{{$modules[0]->SpecialityId}}</td></tr>
        <tr><td>Semester</td><td>{{$modules[0]->Semester}}</td></tr>
        <tr><td><h4>Disciplines</h5></td><td></td></tr>
        @foreach($modules as $module)
            <tr><td>
                    {{$module->NameDiscipline}}<br/>
                    {{$module->ModuleNum}}. {{$module->NameModule}}
                </td>
                <td>
                    Quantity Students: {{$module->quantityStudents}}<br/>
                    Quantity Groups: {{$module->quantityGroups}}<br/>
                </td>
            </tr>
        @endforeach
        </table>
    </div>
    <div class="col-md-6">
        <div class="block"><h3>Actions on the file</h3></div>
        <table id="table2" class="table table-hover ui-datatable"
            <tr>
                <td>
                    Download original file.
                </td>
                <td>
                    <a href="/xls/{{$modules->path}}/{{$modules->name}}" class="btn btn-success btn-sm "> Get original xls </a>
                </td>
            </tr>
            @if(!in_array(Auth::user()->role_id,[4,5,6,7]))
                <tr>
                    <td>
                        Generate all documents of format(2.1) where first number it's number of discipline, second it's number of group
                    </td>
                    <td>
                        <a href="/documents/{{$modules->file_info_id}}/getAllDocuments" class="btn btn-warning btn-sm "> Get all Documents </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        Generate statistics (alpha ver)
                    </td>
                    <td>
                        <a href="/documents/{{$modules->file_info_id}}/getAllStatistics" class="btn btn-warning btn-sm "> View all Statistics </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        Sending E-mails to each student (SMTP)
                    </td>
                    <td>
                        <a href="/documents/{{$modules->file_info_id}}/sendEmails" class="btn btn-warning btn-sm "> Send E-mails</a>
                    </td>
                </tr>
            @endif
            @if(!in_array(Auth::user()->role_id,[2,4,5,6,7]))
                <tr>
                    <td>
                        Change exams grade (only available to inspectors and admin)
                    </td>
                    <td>
                        <a href="/recheck/{{$modules->file_info_id}}/examGrade" class="btn btn-danger btn-sm "> Change Exam Grade </a>
                    </td>
                </tr>
            @endif
            @if(!in_array(Auth::user()->role_id,[3,4,5,6,7]))
                <tr>
                    <td>
                        Delete this document!
                    </td>
                    <td>
                        <a href="/documents/{{$modules->file_info_id}}/remove" class="btn btn-primary btn-sm"> Delete this datas </a>
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
