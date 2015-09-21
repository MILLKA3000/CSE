@extends('admin.layouts.default')
{{-- Content --}}
@section('main')
    <div class="page-header">
        <h3>
            {!! trans("admin/department.list") !!}
            <div class="pull-right">
                <div class="pull-right">

                </div>
            </div>
        </h3>
    </div>
@if (isset($department))
{!! Form::model($department, array('url' => URL::to('department') . '/' . $department->id, 'method' => 'PUT', 'class' => 'bf', 'files'=> true)) !!}
@else
{!! Form::open(array('url' => URL::to('department'), 'method' => 'POST', 'class' => 'bf', 'files'=> true)) !!}
@endif
        <!-- Tabs Content -->
<div class="tab-content">
    <!-- General tab -->
    <div class="tab-pane active" id="tab-general">
        <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
            {!! Form::label('name', trans("admin/department.name"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
            </div>
        </div>

        <div class="form-group  {{ $errors->has('note') ? 'has-error' : '' }}">
            {!! Form::label('note', trans("admin/department.note"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::text('note', null, array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('note', ':message') }}</span>
            </div>
        </div>

        <div class="form-group  {{ $errors->has('users') ? 'has-error' : '' }}">
            {!! Form::label('users', trans("admin/department.checkUser"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::select('users', $users->lists('name', 'id'), (isset($department))?$department->getUser()->user_id:'',array('class' => 'form-control')) !!}
                <span class="help-block">{{ $errors->first('users', ':message') }}</span>
            </div>
        </div>


        <div class="form-group  {{ $errors->has('confirmed') ? 'has-error' : '' }}">
            {!! Form::label('confirmed', trans("admin/department.active"), array('class' => 'control-label')) !!}
            <div class="controls">
                {!! Form::label('active', trans("admin/modal.yes"), array('class' => 'control-label')) !!}
                {!! Form::radio('active', '1', @isset($department)? $department->active : 'false') !!}
                {!! Form::label('active', trans("admin/modal.no"), array('class' => 'control-label')) !!}
                {!! Form::radio('active', '0', @isset($department)? $department->active : 'true') !!}
                <span class="help-block">{{ $errors->first('confirmed', ':message') }}</span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <button type="reset" class="btn btn-sm btn-default">
                <span class="glyphicon glyphicon-remove-circle"></span> {{
				trans("admin/modal.reset") }}
            </button>
            <button type="submit" class="btn btn-sm btn-success">
                <span class="glyphicon glyphicon-ok-circle"></span>
                @if	(isset($user))
                    {{ trans("admin/modal.edit") }}
                @else
                    {{trans("admin/modal.create") }}
                @endif
            </button>
        </div>
    </div>
    {!! Form::close() !!}
    @stop @section('scripts')
        <script type="text/javascript">
            $(function () {
                $("#users").select2()
            });
        </script>
</div>
@stop
