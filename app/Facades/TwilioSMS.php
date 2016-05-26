<?php

namespace myocuhub\Facades;
use Illuminate\Support\Facades\Facade;

class TwilioSMS extends Facade {
	protected static function getFacadeAccessor() {
		return 'sms';
	}
}