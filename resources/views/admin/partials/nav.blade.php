<nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="">CSE alpha 0.2.001</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                   aria-expanded="false"><i class="fa fa-user"></i> {{ Auth::user()->name }} <i
                            class="fa fa-caret-down"></i></a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="{{ URL::to('auth/logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                    </li>
                </ul>
            </li>
    </ul>
    </div>
    <div class="navbar-default sidebar" role="navigation" style="margin-top: 0px;">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="{{url('/')}}">
                        <i class="fa fa-dashboard fa-fw"></i> {!! trans("admin/menu.dashboard") !!}
                    </a>
                </li>
                @if(!in_array(Auth::user()->role_id,[3,4,5,6,7,8]))
                <li>
                    <a href="#">
                        <i class="fa fa-sort-desc"></i> {!! trans("admin/menu.export") !!}
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav collapse">
                        <li>
                            <a href="{{url('excel/loadXML')}}">
                                <i class="glyphicon glyphicon-list"></i>  {!! trans("admin/menu.getXLS") !!}
                            </a>
                        </li>
                        <li>
                            <a href="{{url('xml/loadXMLToDeanery')}}">
                                <i class="glyphicon glyphicon-list"></i> {!! trans("admin/menu.getXMLforDep") !!}
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(!in_array(Auth::user()->role_id,[3,4,5,6,7,8]))
                <li>
                    <a href="#">
                        <i class="fa fa-sort-desc"></i> {!! trans("admin/menu.import") !!}
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav collapse">
                        <li>
                            <a href="{{url('excel/importXLS')}}">
                                <i class="glyphicon glyphicon-list"></i>  {!! trans("admin/menu.importXLS") !!}
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(!in_array(Auth::user()->role_id,[5,6,7]))
                <li>
                    <a href="#">
                        <i class="fa fa-sort-desc"></i> {!! trans("admin/menu.arhive") !!}
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav collapse">
                        <li>
                            <a href="{{url('arhive')}}">
                                <i class="glyphicon glyphicon-list"></i> {!! trans("admin/menu.arhiveExam") !!}
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(!in_array(Auth::user()->role_id,[3,6,7]))
                <li>
                    <a href="#">
                        <i class="fa fa-sort-desc"></i> @if(in_array(Auth::user()->role_id,[4])) {!! trans("admin/menu.getDocs") !!} @else {!! trans("admin/menu.Consulting") !!} @endif
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav collapse">
                        <li>
                            <a href="{{url('teacher')}}">
                                <i class="glyphicon glyphicon-list"></i> @if(in_array(Auth::user()->role_id,[4])) {!! trans("admin/menu.getDocsPart") !!} @else {!! trans("admin/menu.addConsulting") !!}  @endif
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(!in_array(Auth::user()->role_id,[5,6,7]))
                <li>
                    <a href="#">
                        <i class="fa fa-sort-desc"></i> {!! trans("admin/menu.setting") !!}
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav collapse">
                        <li>
                            <a href="{{url('department')}}">
                                <i class="glyphicon glyphicon-list"></i> {!! trans("admin/menu.departments") !!}
                            </a>
                        </li>
                        @if(!in_array(Auth::user()->role_id,[3,4,8]))
                        <li>
                            <a href="{{url('user')}}">
                                <i class="glyphicon glyphicon-list"></i> {!! trans("admin/menu.users") !!}
                            </a>
                        </li>
                        <li>
                            <a href="{{url('language')}}">
                                <i class="fa fa-language"></i> {!! trans("admin/menu.language") !!}
                            </a>
                        </li>
                        @endif
                        @if(!in_array(Auth::user()->role_id,[3,4,5,6,7,8]))
                            <li>
                                <a href="{{url('settings')}}">
                                    <i class="fa fa-language"></i> {!! trans("admin/menu.settings") !!}
                                </a>
                            </li>
                        @endif
                        @if(!in_array(Auth::user()->role_id,[3,4,5,6,7]))
                            <li>
                                <a href="{{url('logs')}}">
                                    <i class="fa fa-language"></i> {!! trans("admin/menu.viewLogs") !!}
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>