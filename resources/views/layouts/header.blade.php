@if(Auth::check())
<div class="row height header-user">
    <div class="col-xs-3 header-user-left hidden-xs"></div>
    <div class="col-xs-12 col-sm-9 header-user-right">
        <div class="header-user-item"><a href="/auth/logout">LOGOUT</a></div>
    </div>
</div>
@endif
<div class="row height header">
    <div class="col-xs-3 hidden-xs header-left"></div>
    <div class="col-xs-12 col-sm-9 header-right">
        <div class="col-xs-3 header-logo">
            <img src="{{URL::asset('images/ocuhub-logo.png')}}" class="img-responsive">
            <div id="loader-container">
                {{-- <p id="loadingText">Loading</p> --}}
            </div>
        </div>
        <div class="col-xs-9 header-menu">
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
                    <ul class="nav navbar-nav arial_bold header_navbar_items">
                        <li class="menu-item" ><a href="/home">HOME</a></li>
                        <li class="menu-item" ><a href="/techsupport">TECH SUPPORT</a></li>
                    </ul>
                </div>
            </nav>
            @endif
        </div>
    </div>
    <!--
    -->
</div>
