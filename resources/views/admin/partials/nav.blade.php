<nav class="navbar navbar-inverse navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">CSE</a>
        <a class="navbar-brand text-right" href="{{url('auth/logout')}}">Logout</a>
    </div>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="{{url('/')}}">
                        <i class="fa fa-dashboard fa-fw"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{url('/language')}}">
                        <i class="fa fa-language"></i> Language
                    </a>
                </li>
                <li>
                    <a href="{{url('/user')}}">
                        <i class="glyphicon glyphicon-user"></i> Users
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>