<?php

namespace myocuhub\Services\Twilio;

use Event;
use Exception;
use Log;
use myocuhub\Events\MakeAuditEntry;
use myocuhub\Services\Twilio\Twilio;

/**
* Messaging Service Class for Twilio
*/
class TwilioMessaging extends Twilio
{
	public function __construct()
	{
		
	}

	public static function send($to, $message){

		try {
			
			$from = parent::getFrom();

			$client = parent::getServiceClient();
			$message = $client->account->messages->sendMessage($from, $to, $message);
			
			return $message->sid;

		} catch (Exception $e) {

			Log::error($e);
            $action = "Attempt to send SMS to $to failed";
            $description = $e->faultstring;
            $filename = basename(__FILE__);
            $ip = '';
            Event::fire(new MakeAuditEntry($action, $description, $filename, $ip));

            return false;

		}

		return false;
		
	}

	public function prepare($view, $attr){
		 return view($view)->with('attr', $attr)->render();
	}

}