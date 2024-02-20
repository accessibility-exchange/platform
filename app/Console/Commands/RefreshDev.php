<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

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
        if (App::environment('production')) {
            $this->error(__('The app:refresh-dev command cannot be run in the ":env" application environment.', ['env' => App::environment()]));

            return 1;
        }

        $this->call('down', ['--render' => 'errors::503']);
        $this->call('migrate:fresh', ['--seeder' => 'DevSeeder']);
        $this->call('up');
    }
}
