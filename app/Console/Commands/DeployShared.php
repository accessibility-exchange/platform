<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployShared extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:shared';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All commands that should be run on all webheads when a container boots.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->call('storage:link');
        $this->call('google-fonts:fetch');

        return 0;
    }
}
