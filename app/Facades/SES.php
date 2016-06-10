<?php

namespace myocuhub\Facades;
use Illuminate\Support\Facades\Facade;

class SES extends Facade {
	protected static function getFacadeAccessor() {
		return 'ses';
	}
}