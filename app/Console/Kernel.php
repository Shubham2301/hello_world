<?php

namespace myocuhub\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \myocuhub\Console\Commands\Inspire::class,
        \myocuhub\Console\Commands\WriteBack::class,
        \myocuhub\Console\Commands\CareConsoleRecallPatients::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('careconsole:recallpatients')
        //          ->withoutOverlapping()
        //          ->daily();
        $schedule->command('writeback')
                 ->withoutOverlapping()
                 ->daily();
    }
}
