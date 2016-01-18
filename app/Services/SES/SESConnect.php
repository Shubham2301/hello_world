<?php

namespace myocuhub\Services\SES;


class SESConnect extends SES
{
//    protected $tokenURL;
//    protected $authorizationURL;
//    protected $redirectURI;
//    protected $btoaCode;
//    protected $ssoLogOnURL;
//    protected $displayCountTimer;
//    protected $iframeWidth;
//    protected $iframeHeight;
//    protected $clientId;


    public function __construct()
    {

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

    public function hasDirectMail(){

        $connection = array();

        $user = \Auth::user();
        $sesEmail = $user->sesemail;

        if(!$sesEmail){
            return false;
        }

        return $sesEmail;
    }

    public function getDirectMail(){

        $config = array();

        $config['sso_logon_url'] = 'http://test.directaddress.net/portal/Home/SSOLogOn';
        $config['authorization_url'] = 'https://test.directaddress.net/SESIDPServer/connect/authorize';
        $config['client_id'] = 'occuhub';
        $config['direct_mail_str'] = $this->hasDirectMail();
        $config['display_count_timer'] = '10';
        $config['redirect_uri'] = '/directmail';
        $config['btoa_code'] = 'occuhub:f70846fec33e4debbe442f470c0bf2d4';
        $config['token_url'] = 'https://test.directaddress.net/SESIDPServer/connect/token';
        $config['iframe_height'] = 800;
        $config['iframe_width'] = 1000;

        return $config;
    }
}
