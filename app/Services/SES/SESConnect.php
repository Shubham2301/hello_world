<?php

namespace myocuhub\Services\SES;

use Auth;
use myocuhub\User;
use Illuminate\Support\Facades\URL;
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

    public function checkScope($userId)
    {

        $user = Auth::user();
        $usertype = intval($user->usertype_id);
        $level = intval($user->level);

        if ($usertype === 2) {
            if ($level === 2) {
                $networkId = $user->userNetwork->first()->network_id;
                $user_networkId = User::find($userId)->userNetwork->first()->network_id;
                if ($networkId != $user_networkId) {
                    return false;
                } else {
                    return true;
                }

            } elseif ($level === 3) {
                $practiceId = User::getPractice($user->id)->id;
                $user_practiceId = User::getPractice($userId)->id;

                if ($practiceId != $user_practiceId) {
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return false;
        }

        return false;

    }
    public function getImpersonationScope()
    {
        $user = Auth::user();
        $usertype = intval($user->usertype_id);
        $level = intval($user->level);
        $scope = [];

        if ($usertype == 2) {
            if ($level === 2) {
                $networkId = $user->userNetwork->first()->network_id;
                $users = User::networkProvidersById($networkId);

                foreach ($users as $user) {
                    if (!$user->sesemail || $user->sesemail == '') {
                        continue;
                    }
                    $scope[] = ['id' => $user->id, 'name' => $user->name];
                }

            } elseif ($level === 3) {
                $practiceId = User::getPractice($user->id)->id;
                $users = User::practiceProvidersById($practiceId);

                foreach ($users as $user) {
                    if (!$user->sesemail || $user->sesemail == '') {
                        continue;
                    }
                    if ($user->id == Auth::user()->id) {
                        continue;
                    }
                    $scope[] = ['id' => $user->id, 'name' => $user->name];
                }
            }
        } else {
            return $scope;
        }
        return $scope;
    }

    public function hasDirectMail()
    {

        $connection = array();
        if (session('impersonation-id') != '') {
            $user = User::find(session('impersonation-id'));
            if (!$user) {
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

    public function getDirectMail()
    {

        $config = array();

        $config['sso_logon_url'] = env('SES_LOGON_URL');
        $config['authorization_url'] = 'https://direct.ocuhub.com/sesidpserver/connect/authorize';
        $config['client_id'] = env('SES_CLIENT_ID');
        $config['direct_mail_str'] = $this->hasDirectMail();
        $config['display_count_timer'] = '10';
        $config['redirect_uri'] = URL::to('/directmail');
        $config['btoa_code'] = env('SES_BTOA_CODE');
        $config['token_url'] = 'https://direct.ocuhub.com/sesidpserver/connect/token';
        $config['iframe_height'] = 900;
        $config['iframe_width'] = 1000;
        $config['sso_logoff_url'] = env('SES_LOGOFF_URL');

        return $config;
    }
}
