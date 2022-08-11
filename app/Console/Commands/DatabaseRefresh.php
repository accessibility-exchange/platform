<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes and seeds the database for development work.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // don't run command in production or testing environment
        if (in_array(config('app.env'), ['testing', 'production']) !== true) {
            $this->call('migrate:fresh', ['--force' => true]);
            $this->call('db:seed', ['--class' => 'DevSeeder', '--force' => true]);
        }

        return 0;
    }
}
