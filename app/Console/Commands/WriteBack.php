<?php

namespace myocuhub\Console\Commands;

use DateTime;
use Illuminate\Console\Command;
use myocuhub\Facades\Writeback4PC;
use myocuhub\User;

class WriteBack extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'writeback';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Retrieving appointment schedules from 4PC and updating Ocuhub Database.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {

		$this->comment(PHP_EOL . $this->description . PHP_EOL);

		$startDate = new DateTime();

		$providers = User::get4PCProviderNPIs();
		$schedules = [];

		foreach ($providers as $provider) {
			if ($provider->npi == '') {
				continue;
			}

			$input = [];
			$input['NPI'] = $provider->npi;
			$input['Start'] = $startDate->format('Y-m-d');
			$input['DaysForward'] = 14;

			$schedule = WriteBack4PC::ProviderApptSchedule($input);

			if (sizeof($schedule->OcuHub_ApptScheduleResult) != 0) {
				$schedules[] = [
					'npi' => $provider->npi,
					'schedule' => $schedule->OcuHub_ApptScheduleResult->ApptDetail,
				];
			}
		}

		$writeBackResult = WriteBack4PC::OcuhubAppointmentWriteback($schedules);

	}
}
