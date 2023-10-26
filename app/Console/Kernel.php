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
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('db:seed:backup --all') // use custom command to make sure that the commands are chained
            ->daily() // Run daily at midnight
            ->environments(['staging', 'dev', 'local', 'production']) // only run for APP_ENV tagged staging, dev, local, or production
            ->timezone('America/Los_Angeles') // Run as PST timezone
            ->onOneServer(); // run only on a single server at once

        $schedule->command('app:refresh-dev') // use custom hcommand to make sure that te commands are chained
            ->dailyAt('00:15') // Run daily at 12:15 am
            ->environments(['staging', 'dev', 'local']) // only run for APP_ENV tagged staging, dev, or local
            ->timezone('America/Los_Angeles') // Run as PST timezone
            ->onOneServer(); // run only on a single server at once

        $schedule->command('notifications:remove:old --days=30') // remove notifications older than 30 days old and read
            ->daily() // Run daily at midnight
            ->timezone('America/Los_Angeles') // Run as PST timezone
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
