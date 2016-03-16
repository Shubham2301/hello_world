<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;

class WriteBack4PC extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'writeback4pc';

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
		//
	}
}
