<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;
use myocuhub\Models\TwoFactorAuth;

class CleanTwoFactorAuth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'two-factor-auth:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans two_factor_auth table for entries older than a day';

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
        TwoFactorAuth::clean();
    }
}
