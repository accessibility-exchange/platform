<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:refresh') // use custom command to make sure that the commands are chained
            ->daily() // Run daily at midnight
            ->environments(['staging', 'dev', 'local']) // only run for APP_ENV tagged staging, dev, or local
            ->onOneServer(); // run only on a single server at once

        $schedule->command('notifications:remove:old 30') // remove notifications older than 30 days old and read
        ->daily() // Run daily at midnight
        ->onOneServer(); // run only on a single server at once
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
