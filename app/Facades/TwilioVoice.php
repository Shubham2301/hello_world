<?php

namespace myocuhub\Facades;
use Illuminate\Support\Facades\Facade;

class TwilioVoice extends Facade {
	protected static function getFacadeAccessor() {
		return 'voice';
	}
}