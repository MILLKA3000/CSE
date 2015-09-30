@extends('admin.layouts.default')

{{-- Web site Title --}}
@section('title') {!! $title !!} :: @parent @stop

{{-- Content --}}
@section('main')
    <h3>
        {{$title}}
    </h3>
    <div class="row">
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
                            <div>{{ trans("admin/modules/getExcel.xmlGetTitle") }}!</div>
                        </div>
                    </div>
                </div>
                </a>
            </div>
        </div>

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


        {{--<div class="col-lg-2 col-md-2">--}}
            {{--<div class="panel panel-info">--}}
                {{--<a href="{{URL::to('excel/get')}}">--}}
                {{--<div class="panel-heading">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-xs-3">--}}
                            {{--<i class="glyphicon glyphicon-bullhorn fa-2x"></i>--}}
                        {{--</div>--}}
                        {{--<div class="col-xs-9 text-right">--}}
                            {{--<div class="huge"></div>--}}
                            {{--<div>{{ trans("admin/modules/statistic.title") }}!</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}

    </div>
@endsection
