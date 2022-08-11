<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployGlobal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:global';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All commands that should be run on a single webhead when a container boots.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        // Split migrate commands between production and development versions
        if (config('app.env') === 'production') {
            $this->call('migrate', ['--step' => true, '--force' => true]);
        } else {
            $this->call('db:refresh');
        }

        $this->call('optimize:clear');
        $this->call('optimize');
        $this->call('event:cache');

        return 0;
    }
}
