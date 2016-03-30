<?php

namespace myocuhub\Services\SES;

use Auth;
use myocuhub\User;

class SESConnect extends SES {
//    protected $tokenURL;
	//    protected $authorizationURL;
	//    protected $redirectURI;
	//    protected $btoaCode;
	//    protected $ssoLogOnURL;
	//    protected $displayCountTimer;
	//    protected $iframeWidth;
	//    protected $iframeHeight;
	//    protected $clientId;

	public function __construct() {

//        $this->$tokenURL = 'https://test.directaddress.net/SESIDPServer/connect/token';
		//        $this->$authorizationURL = 'https://test.directaddress.net/SESIDPServer/connect/authorize';
		//        $this->$redirectURI = 'group/guest/direct-mail';
		//        $this->$btoaCode = 'occuhub:f70846fec33e4debbe442f470c0bf2d4';
		//        $this->$ssoLogOnURL = 'http://test.directaddress.net/portal/Home/SSOLogOn';
		//        $this->$displayCountTimer = 10;
		//        $this->$iframeWidth = 1000;
		//        $this->$iframeHeight = 800;
		//        $this->$clientId = 'occuhub';

	}

	public function hasDirectMail() {

		$connection = array();
        if(session('impersonation-id') != '' ){
            $user = User::find(session('impersonation-id'));
            if(!$user){
                $user = Auth::user();
            }
        } else {
            $user = Auth::user();
        }
		
		$sesEmail = $user->sesemail;

		if (!$sesEmail) {
			return false;
		}

		return $sesEmail;
	}

	public function getDirectMail() {

		$config = array();

		$config['sso_logon_url'] = 'https://direct.ocuhub.com/portalsso/Home/SSOLogOn';
		$config['authorization_url'] = 'https://direct.ocuhub.com/sesidpserver/connect/authorize';
		$config['client_id'] = env('SES_CLIENT_ID');
		$config['direct_mail_str'] = $this->hasDirectMail();
		$config['display_count_timer'] = '10';
		$config['redirect_uri'] = env('SES_REDIRECT_URI');
		$config['btoa_code'] = env('SES_BTOA_CODE');
		$config['token_url'] = 'https://direct.ocuhub.com/sesidpserver/connect/token';
		$config['iframe_height'] = 800;
		$config['iframe_width'] = 1000;
		$config['sso_logoff_url'] = 'https://direct.ocuhub.com/sesidpserver/connect/endsession';

		return $config;
	}
}
