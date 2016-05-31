<?php

namespace myocuhub\Services\Twilio;

/**
* Voice Service Class for Twilio
*/
class TwilioVoice extends Twilio
{
	function __construct()
	{
		
	}

	public static function call($to, $twiML){
		
		$from = parent::getFrom();
		$client = parent::getServiceClient();

		try {
			
			$call = $client->account->calls->create($from, $to, $twiML);

		} catch (Exception $e) {
			
			Log::error($e);
            $action = "Attempt to call on $to failed";
            $description = $e->faultstring;
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

            return false;

		}

		return false;

	}

}