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

        <div class="row form-group">
            <div class="col-md-5">
                <br/>
                <span class="h5 nv-line">All Discipline</span>
                <br/>
                <br/>
                <div class="form-group">
                    <div class="col-md-12 ">
                        {!! Form::select('discipline[]',$discipline->lists('NameDiscipline', 'DisciplineVariantID'), '', array('multiple' => true, 'class'=>'form-control all-discipline', 'style'=>'    height: 257px!important;
                        width: 100%!important;
                        border: 1px #000!important;
                        position: initial;')) !!}
                    </div>
                </div>

            </div>

            <!-- Block Add, Remove Allowed discipline-->
            <div class="col-md-2 text-center" style="position: relative; top:150px; font-size: 150%">
                @if(!in_array(Auth::user()->role_id,[5,6,7,8]))
                    <div class="row">
                        <i class="btn btn-default discipline-add"><span class="glyphicon glyphicon-forward"></span></i>
                    </div>
                    <div class="row">
                        <i class="btn btn-default discipline-remove"><span class="glyphicon glyphicon-backward"></span></i>
                    </div>
                @endif
            </div>

            <div class="col-md-5">
                <br/>
                <span class="h5 nv-line">Allowed Discipline</span>
                <br/>
                <br/>
                <div class="form-group">
                    <div class="col-md-12 ">
                        {!! Form::select('discipline_allowed[]',$discipline_allowed->lists('NameDiscipline', 'DisciplineVariantID'), '', array('multiple' => true, 'class'=>'form-control allowed-discipline', 'style'=>'height:290px;')) !!}
                    </div>
                </div>

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
    @if(!in_array(Auth::user()->role_id,[5,6,7,8]))
        <div class="form-group">
            <div class="col-md-12">
                <button type="submit" class="btn btn-sm btn-success sace">
                    <span class="glyphicon glyphicon-ok-circle"></span>
                    {{ trans("admin/modal.save") }}
                </button>
            </div>
        </div>
    @endif
</div>
    {!! Form::close() !!}
    @stop
@section('scripts')
        <script type="text/javascript">
            $(function () {
                $("#users").select2();
                $(".all-discipline").select2({
                    placeholder: "Select a state",
                    allowClear: true
                });
            });
            $('.discipline-add').on('click',function(){
                if ($('.all-discipline option:selected').val() != null) {
                    var tempSelect = $('.all-discipline option:selected').val();
                    $('.all-discipline option:selected').remove().appendTo('.allowed-discipline');
                    $(".all-countries").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
                    $(".allowed-discipline").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
                    $(".allowed-discipline").val(tempSelect);
                    tempSelect = '';
                    _sort_multi_select('.allowed-discipline');
                } else {
                    alert("Before add please select any position.");
                }
            })

            /*
             action for removing countries from block 'allowed countries'
             */
            $('.discipline-remove').on('click',function(){
                if ($('.allowed-discipline option:selected').val() != null) {
                    var tempSelect = $('.allowed-discipline option:selected').val();
                    $('.allowed-discipline option:selected').remove().appendTo('.all-discipline');
                    $(".all-discipline").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
                    $(".all-discipline").attr('selectedIndex', '-1').find("option:selected").removeAttr("selected");
                    $(".all-discipline").val(tempSelect);
                    tempSelect = '';
                    _sort_multi_select('.all-discipline');
                } else {
                    alert("Before add please select any position.");
                }
            })

            /*
             function for sorting array in select
             */
            function _sort_multi_select(selector){
                var sel = $(selector);
                var opts_list = sel.find('option');
                opts_list.sort(function(a, b) { return $(a).text() > $(b).text(); });
                sel.html('').append(opts_list);
            }

            /*
             pre sorting select (not use, if array receive sorted)
             */
            _sort_multi_select('.allowed-discipline');
            _sort_multi_select('.all-discipline');

            $('.bf').submit(function(even) {
                even.preventDefault();
                $(".allowed-discipline option").attr('selected','selected');
                this.submit();
            });

        </script>
@stop
