@extends('layouts.app')

{{-- Web site Title --}}
@section('title') {!!  trans('site/user.login') !!} :: @parent @stop

{{-- Content --}}
@section('content')
    <div class="row">
        <div class="page-header">
            <h2>{!! trans('site/user.teacher_login') !!}</h2>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="form-group">
                <div class="col-md-4 col-md-offset-4">
                    <a class="btn btn-primary" href="{{ URL::to('/googleOauth2') }}">{!! trans('site/user.google_login_button') !!}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="page-header">
            <h2>{!! trans('site/user.login_to_account') !!}</h2>
        </div>
    </div>
    <?= (isset($google_err)) ? $google_err : '' ?>
    <div class="ruslan-login-spoiler">
        <input type="checkbox"> Administrator
            <div class="ruslan-login-box">
                <div class="container-fluid">
                    <div class="row">
                        {!! Form::open(array('url' => URL::to('auth/login'), 'method' => 'post', 'files'=> true)) !!}
                        <div class="col-md-offset-2 form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                            {!! Form::label('email', "E-Mail Address", array('class' => 'control-label col-md-2')) !!}
                            <div class="controls col-md-4">
                                {!! Form::text('email', null, array('class' => 'form-control')) !!}
                                <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-2 form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                            {!! Form::label('password', "Password", array('class' => 'control-label col-md-2')) !!}
                            <div class="controls col-md-4">
                                {!! Form::password('password', array('class' => 'form-control')) !!}
                                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-offset-4">
                                <div class="checkbox">
                                    <button type="submit" class="btn btn-primary" style="margin-right: 15px;">
                                        Login
                                    </button>
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    {!! Form::close() !!}
@endsection