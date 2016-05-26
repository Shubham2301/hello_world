<?php

namespace myocuhub\Services\Twilio;

/**
* Parent Class for Twilio Service 
*/
class Twilio
{
	
	private $accountSID;
	private $authToken;
	private $from;

	function __construct()
	{
		self::$accountSID = env('TWILIO_ACCOUNT_SID');
		self::$authToken = env('TWILIO_AUTH_TOKEN');
		self::$from = env('TWILIO_FROM_NUMBER');
	}


    /**
     * Gets the value of accountSID.
     *
     * @return mixed
     */
    public static function getAccountSID()
    {
        return self::$accountSID;
    }

    /**
     * Sets the value of accountSID.
     *
     * @param mixed $accountSID the account
     *
     * @return self
     */
    private function _setAccountSID()
    {
        $this->accountSID = env('TWILIO_ACCOUNT_SID');

        return $this;
    }

    /**
     * Gets the value of authToken.
     *
     * @return mixed
     */
    public static function getAuthToken()
    {
        return self::$authToken;
    }

    /**
     * Sets the value of authToken.
     *
     * @param mixed $authToken the auth token
     *
     * @return self
     */
    private function _setAuthToken()
    {
        $this->authToken = env('TWILIO_AUTH_TOKEN');

        return $this;
    }


    /**
     * Gets the value of from.
     *
     * @return mixed
     */
    public function getFrom()
    {
        return self::$from;
    }

    /**
     * Sets the value of from.
     *
     * @param mixed $from the from
     *
     * @return self
     */
    private function _setFrom()
    {
        $this->from = env('TWILIO_AUTH_TOKEN');

        return $this;
    }
}