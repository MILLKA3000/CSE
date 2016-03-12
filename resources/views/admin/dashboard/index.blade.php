@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <h3>
        {{$title}}
    </h3>
    <div class="row">
        @if(!in_array(Auth::user()->role_id,[3,4,5,6,7,8]))
        <div class="col-lg-2 col-md-2">
            <div class="panel panel-info">
                <a href="{{URL::to('excel/loadXML')}}">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-export fa-2x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"></div>
                            <div>{!! trans("admin/modules/getExcel.xmlGetTitle") !!}!</div>
                        </div>
                    </div>
                </div>
                </a>
            </div>
        </div>
        @endif
        @if(!in_array(Auth::user()->role_id,[3,4,5,6,7,8]))
        <div class="col-lg-2 col-md-2">
            <div class="panel panel-info">
                <a href="{{URL::to('excel/importXLS')}}">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon glyphicon-import fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div> {{ trans("admin/modules/Excel.loadXLStitle") }}!</div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
        @endif
        @if(!in_array(Auth::user()->role_id,[4,5,6,7]))
        <div class="col-lg-2 col-md-2">
            <div class="panel panel-info">
                <a href="{{URL::to('arhive')}}">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon glyphicon-tasks fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>{{ trans("admin/modules/arhive.title") }}!</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif
        @if(!in_array(Auth::user()->role_id,[3,6,7]))
        <div class="col-lg-2 col-md-2">
            <div class="panel panel-info">
                <a href="{{URL::to('teacher')}}">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon glyphicon-export fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"></div>
                                <div>@if(in_array(Auth::user()->role_id,[4]) ){{ trans("admin/menu.getDeanerDocs") }} @else {{ trans("admin/menu.addConsulting") }}! @endif </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif
            @if(!in_array(Auth::user()->role_id,[3,4,5,6,7,8]))
                <div class="col-lg-2 col-md-2">
                    <div class="panel panel-info">
                        <a href="{{URL::to('settings')}}">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="glyphicon glyphicon-export fa-2x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"></div>
                                        <div>{!! trans("admin/menu.settings") !!}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            @if(!in_array(Auth::user()->role_id,[3,4,5,6,7]))
                <div class="col-lg-2 col-md-2">
                    <div class="panel panel-info">
                        <a href="{{URL::to('logs')}}">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="glyphicon glyphicon-tasks fa-2x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"></div>
                                        <div>{!! trans("admin/menu.viewLogs") !!}</div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
    </div>
@endsection
