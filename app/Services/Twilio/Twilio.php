<?php

namespace myocuhub\Services\Twilio;

use Services_Twilio;

/**
* Parent Class for Twilio Service 
*/
class Twilio
{
	
	private static $accountSID;
	private static $authToken;
	private static $from;

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
     * @return self
     */
    private function _setAccountSID()
    {
        self::$accountSID = env('TWILIO_ACCOUNT_SID');
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
     * @return self
     */
    private function _setAuthToken()
    {
        self::$authToken = env('TWILIO_AUTH_TOKEN');
    }


    /**
     * Gets the value of from.
     *
     * @return mixed
     */
    public static function getFrom()
    {
        return self::$from;
    }

    /**
     * Sets the value of from.
     *
     * @return self
     */
    private function _setFrom()
    {
        self::$from = env('TWILIO_AUTH_TOKEN');
    }

    /**
     * 
     * @return Services_Twilio object
     */
    public static function getServiceClient(){

		return new Services_Twilio(self::getAccountSID(), self::getAuthToken());

	}

	/**
     * 
     * @return Services_Twilio object
     */
	public static function generateTwiML($message = ''){

		$response = new Services_Twilio_Twiml();

		$response->say($message);

		return $response;
	}
}