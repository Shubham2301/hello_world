<?php

namespace myocuhub\Providers;

use App;
use Illuminate\Support\ServiceProvider;

class WebScheduling4PCServiceProvider extends ServiceProvider {
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
		App::bind('webscheduling4pc', function () {
			return new \myocuhub\Services\PatientCare\WebScheduling4PC;
		});
	}
}
