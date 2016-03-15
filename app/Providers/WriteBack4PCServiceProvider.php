<?php

namespace myocuhub\Providers;

use App;
use Illuminate\Support\ServiceProvider;

class WriteBack4PCServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {
		App::bind('writeback4pc', function () {
			return new \myocuhub\Services\PatientCare\WriteBack4PC;
		});
	}
}
