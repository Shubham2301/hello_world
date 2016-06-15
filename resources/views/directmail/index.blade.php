@extends('layouts.master')

@section('title', 'My Ocuhub - Administration')

@section('imports')
<link rel="stylesheet" type="text/css" href="{{elixir('css/directmail.css')}}">
<script type="text/javascript" src="{{elixir('js/sesconnect.js')}}"></script>
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')

    @if (Session::has('no_direct_mail'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><i class="fa fa-check-circle fa-lg fa-fw"></i> Failure. &nbsp;</strong>
        {{ Session::pull('no_direct_mail') }}
    </div>
    @else
    <div class="content-section active" id="directmail-console">


        @if(session('impersonation-id') != '' )
        <div style="position:absolute">
            <div style="background-color:#d3eefa;padding:0.25em;border-radius:100%;width:2.5em;display:inline-block">
                <img style="width:2em" src="{{ asset('/images/impersonate-icon-01.png') }}" alt="">
            </div>
            <span style="display:inline-block"> &nbsp;Proxying <span class="arial_bold">{{ session('impersonation-name') }}</span>&nbsp;</span>
            <form action="/directmail/endimpersonate" method="POST" id="reload-active-direct" style="display:inline-block">
               {{ csrf_field() }}
                <div onclick="endImpersonation()" style="display:inline-block">
                    <img src="{{ asset('/images/close-white.png') }}" style="margin-top: -0.2em;width: 0.7em;" alt="">
                </div>
            </form>
        </div>
        @else
            @if(sizeof($impersonation) > 0)
            <div id="impersonateBtn">Proxy User</div>
            @endif
        @endif

        <img id="loadingImg" alt="Loading..."   src="{{ asset('/images/ajax-loader.gif') }}" style="width:1em;display: none;">
        <div id="refreshBtn" onclick="refreshPage()"><img src="{{ elixir('images/sidebar/refresh-icon-01.png') }}" alt=""></div>
        <button id="getCodeBtn" class="btn dismiss_button" style="width:20%;display: none;">Get Code</button>

        @if (Session::has('request_failed_msg'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>
                    <i class="fa fa-check-circle fa-lg fa-fw"></i> Failure. &nbsp;
            </strong>
            {{ Session::pull('request_failed_msg') }}
        </div>
        @endif
        <form id="ocuhubSESFm" action="{{ $ses['sso_logon_url'] }}" method="post" target="_blank">
            <input id="id_token" type='hidden' name='token' value="" />
        </form>
        <iframe id="ocuhubSESiframeId" name="ocuhubSESiframe" src="" frameborder="0" style="display:none;width: 100%;flex: 1 1 auto;margin-top: 3em;"></iframe>


        <div>
            <form id="end-impersonation-form" target="end-impersonation-iframe" action="https://direct.ocuhub.com/sesidpserver/connect/endsession" method="GET"></form>
            <iframe id="end-impersonation-iframe" name="end-impersonation-iframe" src="" frameborder="0" style="display:none;"></iframe>
        </div>
    </div>

    <script>

        $('#impersonateBtn').on('click', function(){
            $('#impersonateModal').modal('show');
        });

        $("#end-impersonation-form").submit(function () {
            @if(session('impersonation-id') != '')
                $("#reload-active-direct").submit();
            @else
                $('#impersonation-form').submit();
            @endif
        });

        function beginImpersonation() {
			if($('#impersonateuser').val() == '0'){
				$('p.alert_message').text('please select a user');
				$('#alert').modal('show');
				return;
			}
             $("#end-impersonation-form").submit();

        }

        function endImpersonation () {
            $("#end-impersonation-form").submit();
        }

        var timerCount = '{{ $ses['display_count_timer'] }}';

        function refreshPage() {
            window.location.href = '{{ $ses['redirect_uri'] }}';
        }

        function resizeIframe(obj) {
            obj.style.height = screen.width + 'px';
            var width = screen.width - 250;
            obj.setAttribute('width', ((window.innerWidth || document.body.clientWidth) - 250));
            obj.setAttribute('height', window.innerWidth || document.body.clientWidth);
        }

        if (timerCount == null || timerCount == 'null') {
            timerCount = 10;
        }

        function updateTimer() {
            setTimeout(updateTimer, 1000);
            if (timerCount > 0) {
                timerCount--;
            }
        }

        function submitform(token) {
            document.getElementById("id_token").value = token;
            document.getElementById('ocuhubSESiframeId').style.display = 'block';
            document.forms["ocuhubSESFm"].setAttribute("target", "ocuhubSESiframe");
            document.forms["ocuhubSESFm"].submit();
        }

        var code;
        var token;

        if (window.location.search) {
            code = processCodeCallback();
            getToken();
        }

        document.getElementById("getCodeBtn").addEventListener("click", getCode, false);

        function getCode() {
            document.getElementById('loadingImg').style.display = 'block';
            var authorizationUrl = '{{ $ses['authorization_url'] }}';
            var client_id = '{{ $ses['client_id'] }}';
            // occuhubsso@test.directaddress.net
            var login_hint = '{{ $ses['direct_mail_str'] }}';
            //var login_hint = 'occuhubsso@test.directaddress.net';
            var redirect_uri = '{{ $ses['redirect_uri'] }}';
            var response_type = "code";
            var scope = "openid";
            var state = Date.now() + "" + Math.random();

            localStorage["state"] = state;

            var url =
                authorizationUrl + "?" +
                "client_id=" + encodeURI(client_id) + "&" +
                "login_hint=" + encodeURI(login_hint) + "&" +
                "redirect_uri=" + encodeURI(redirect_uri) + "&" +
                "response_type=" + encodeURI(response_type) + "&" +
                "scope=" + encodeURI(scope) + "&" +
                "state=" + encodeURI(state);
           	window.location = url;
        }

        function processCodeCallback() {
            var search = window.location.search.substr(1);
            var result = search.split('&').reduce(function (result, item) {
                var parts = item.split('=');
                result[parts[0]] = parts[1];
                return result;
            }, {});

            if (!result.error) {
                if (result.state !== localStorage["state"]) {
                    //show("invalid state");
                } else {
                    localStorage.removeItem("state");
                    return result.code;
                }
            }
        }

        function getToken() {
            document.getElementById('loadingImg').style.display = 'block';
            var xhr = new XMLHttpRequest();
            xhr.onload = function (e) {
                if (xhr.status >= 400) {
                    document.getElementById('loadingImg').style.display = 'none';
                    document.getElementById('newWindowMsg').style.display = 'none';
                    document.getElementById('errorMsgTxt').style.display = 'block';
                } else {
                    var result = JSON.parse(xhr.response);
                    document.getElementById('loadingImg').style.display = 'none';
                    submitform(result.id_token);
                }
            };
            xhr.onerror = function (e) {
                document.getElementById('errorMsgTxt').style.display = 'block';
                console.log(e, xhr);
                logError('\n'+e);
            };

            xhr.open("POST", "{{ $ses['token_url'] }}", true);
            xhr.setRequestHeader("Authorization", "Basic " + btoa("{{ $ses['btoa_code'] }}"));
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send('grant_type=authorization_code&code=' + code + '&redirect_uri={{ $ses['redirect_uri'] }}');
        }

        if (!window.location.search) {
           document.getElementById("getCodeBtn").click();
        }
    </script>

    @endif

@include('directmail.impersonation')

@endsection
@section('mobile_sidebar_content')
@include('layouts.sidebar')
@endsection
