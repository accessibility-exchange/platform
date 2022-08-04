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
        $this->call('migrate:fresh', ['--force']);
        $this->call('db:seed', ['DevSeeder', '--force']);

        return 0;
    }
}
