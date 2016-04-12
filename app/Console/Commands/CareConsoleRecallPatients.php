<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;

class CareConsoleRecallPatients extends Command
{
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
    protected $description = 'Moving patients marked for recall back to Care Console';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment(PHP_EOL . $this->description . PHP_EOL);

        // $consolPatients = Careconsole::all();

        // foreach ($consolPatients as $console) {
        //     $console->recall_date = null;
        //     $console->archived_date = null;
        //     $date = new DateTime();
        //     $console->stage_id = 1;
        //     $console->stage_updated_at = $date->format('Y-m-d H:m:s');
        //     $console->entered_console_at = $date->format('Y-m-d H:m:s');
        //     $console->save();
        // }
    }
}
