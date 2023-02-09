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
        $this->call('optimize:clear');
        $this->call('event:clear');
        $this->call('icons:clear');
        $this->call('view:clear');
        $this->call('route:clear');
        $this->call('config:clear');
        $this->call('event:cache');
        $this->call('icons:cache');
        $this->call('view:cache');
        $this->call('route:cache');
        $this->call('config:cache');
        $this->call('google-fonts:fetch');
        $this->call('optimize');
        $this->call('migrate', ['--step' => true, '--force' => true]);

        return 0;
    }
}
