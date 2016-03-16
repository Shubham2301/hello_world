<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;

class CareConsoleRecallPatients extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'careconsole:recallpatients';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Moves patients marked for recall back to Care Console';

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

	}
}
