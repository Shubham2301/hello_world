<div class="row height header">
    <div class="col-xs-3 hidden-xs header-left">
        <div class="col-xs-8 col-xs-offset-2 network_logo">
            @if( Auth::check() && session('user-level') > 1) )
            <img src="{{URL::asset('images/networks/network_'. Auth::user()->getNetwork(Auth::user()->id)->id .'.png')}}" onerror="this.src = '{{URL::asset('images/networks/default_network_logo.png')}}'">
            @endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-9 header-right">
        <div class="col-xs-3 header-logo">
            <img src="{{URL::asset('images/ocuhub-logo.png')}}" class="img-responsive">
            <div id="loader-container">
                {{--
                <p id="loadingText">Loading</p> --}}
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
                        <li class="menu-item"><a href="/home">HOME</a></li>
                        <li class="menu-item"><a href="/techsupport">TECH SUPPORT</a></li>
                        <li class="menu-item"><span class="ocuhub_sso_logoff logout_btn"><span onclick="singleSignOff()">LOGOUT</span></span>
                    </li>
                </ul>
                <div class="ocuhub_sso_logoff">
                <form id="ses_logout_form" target="ses_logout_iframe" action="https://direct.ocuhub.com/sesidpserver/connect/endsession" method="GET"></form>
                <iframe id="ses_logout_iframe" name="ses_logout_iframe" src="" frameborder="0" style="display:none;"></iframe>
            </div>
        </div>
    </nav>
    @endif
</div>
</div>
<!--
-->
</div>