<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;
use myocuhub\Facades\Hedis;

class HedisSupplement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'HedisSupplement:generate {network_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and send the Hedis Supplementary file to the network sftp server'; 

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
        $this->info('File generation and transfer in progress');
        $network_id = $this->argument('network_id');

        // \myocuhub\Facades\Hedis::index($network_id);
        Hedis::index($network_id);
        // app('myocuhub\Http\Controllers\CareConsole\CareConsoleController')->generateHEDISFile($network_id);
    }
}
