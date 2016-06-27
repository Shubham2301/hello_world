<?php

namespace myocuhub\Console\Commands;

use Illuminate\Console\Command;

class PostAppointmentEngagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pee:post-appt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        // get list of appointments for which post appointment engagement is pending
        // get patient preferences
        // prepare jobs for each stack on SMS, Phone, Email
    }
}
