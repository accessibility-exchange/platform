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
     */
    public function handle()
    {
        $this->call('view:cache');
        $this->call('google-fonts:fetch');
        $this->call('migrate', ['--step' => true, '--force' => true]);
    }
}
