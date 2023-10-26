<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshDev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs a development database refresh.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('down');
        if (in_array(config('app.env'), ['dev']) !== true) {
            $this->call('migrate:fresh --seeder=DevSeeder');
        }
        $this->call('db:seed:backup --all --restore --from=production');
        $this->call('up');
    }
}
