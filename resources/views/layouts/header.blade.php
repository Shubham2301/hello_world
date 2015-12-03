<div class="row height header-user">
    <div class="col-xs-3 header-user-left hidden-xs"></div>
    <div class="col-xs-12 col-sm-9 header-user-right">
        <div class="header-user-item"><span>LOGOUT</span></div>
    </div>
</div>
<div class="row height header">
    <div class="col-sm-3 hidden-xs"></div>
    <div class="col-sm-2 header-logo">
        <img src="{{URL::asset('images/ocuhub-logo.png')}}" class="img-responsive">
    </div>

    <div class="col-sm-7 header-menu">
        @if(Auth::check())
        <div class="menu-item active" data-id="care-console">
            <span>Care Console</span>
        </div>
        <div class="menu-item" data-id="payer-console">
            <span>Payer Console</span>
        </div>
        <div class="menu-item" data-id="admin-console">
            <span>Administration</span>
        </div>
        @endif
    </div>
<!--

    <div class="col-sm-6 header-menu">
        <nav class="navbar navbar-default" role="navigation">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="example-navbar-collapse">

                <ul class="nav navbar-nav">
                    <li class="menu-item active" data-id="care-console">Care Console</li>
                    <li class="menu-item" data-id="payer-console">Payer Console</li>
                    <li class="dropdown">
                        <span  class="menu-item dropdown-toggle" data-toggle="dropdown">Administration  <b class = "caret"></b></span>
                        <ul class="dropdown-menu">
                            <li class="menu-item" data-id="user-admin-console"><span>Users</span></li>
                            <li class="menu-item" data-id="roles-admin-console"><span>Roles</span></li>
                        </ul>

                    </li>

                </ul>
            </div>

        </nav>
    </div>
-->
</div>
