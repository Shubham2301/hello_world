<div class="row height header-user">
    <div class="col-xs-3 header-user-left hidden-xs"></div>
    <div class="col-xs-12 col-sm-9 header-user-right">
        <div class="header-user-item"><a href="/auth/logout">LOGOUT</a></div>
    </div>
</div>
<div class="row height header">
    <div class="col-sm-4 hidden-xs"></div>
    <div class="col-sm-4 col-md-3 header-logo">
        <img src="{{URL::asset('images/ocuhub-logo.png')}}" class="img-responsive">
    </div>

    <div class="col-sm-5 col-md-6 header-menu">
        @if(Auth::check())
        <nav class="navbar navbar-default" role="navigation">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#ocuhub-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="ocuhub-navbar-collapse">

                <ul class="nav navbar-nav">
                    @can('view-report')
                    <li class="menu-item" >Reporting</li>
                    @endcan
                    @can('create-user')
                    <li class="dropdown">
                        <span  class="menu-item dropdown-toggle" data-toggle="dropdown">Administration  <b class = "caret"></b></span>
                        <ul class="dropdown-menu">
                            <li class="menu-item" data-id="admin-user-console"><a href="/users">Users</a></li>
                            <li class="menu-item" data-id="admin-role-console"><a href="/roles">Roles</a></li>
                            <li class="menu-item" data-id="admin-permission-console"><a href="/permissions">Permissions</a></li>
                        </ul>
                    </li>
                    @endcan
                </ul>
            </div>

        </nav>
        @endif
    </div>

</div>
