<?php

namespace myocuhub\Facades;
use Illuminate\Support\Facades\Facade;

class Voice extends Facade {
	protected static function getFacadeAccessor() {
		return 'voice';
	}
}