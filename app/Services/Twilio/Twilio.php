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
     * @return self
     */
    private function _setFrom()
    {
        $this->from = env('TWILIO_AUTH_TOKEN');

        return $this;
    }

    /**
     * 
     * @return Services_Twilio object
     */
    public static function getServiceClient(){

		return new Services_Twilio(parent::getAccountSID(), parent::getAuthToken());

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