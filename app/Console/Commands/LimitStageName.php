<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;
use myocuhub\Models\CareconsoleStage;

class LimitStageName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'limitstagenames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $stages = CareconsoleStage::all();
        foreach ($stages as $stage) {
            $name = $stage->display_name;
             $stage->display_name = str_limit($name, 27, '')."\n";
             $stage->save();
        }
    }
}
