<?php

namespace myocuhub\Console\Commands;

use DateTime;
use Illuminate\Console\Command;
use myocuhub\Facades\WriteBack4PC;

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
	protected $description = 'Retrieves appointment schedules form 4PC and updates Ocuhub Database.';

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

		$input = [];
		$input['NPI'] = '991234567';
		$input['Start'] = $startDate->format('Y-m-d');
		$input['DaysForward'] = 2;

		$schedule = WriteBack4PC::ProviderApptSchedule($input);

	}
}
