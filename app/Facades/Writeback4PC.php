<?php

namespace myocuhub\Facades;
use Illuminate\Support\Facades\Facade;

class Writeback4PC extends Facade {
	protected static function getFacadeAccessor() {
		return 'writeback4pc';
	}
}