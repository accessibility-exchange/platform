<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployInitial extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All commands that should be run when a container boots.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // split migrate commands between production and development versions
        if (config('app.env') === 'production') {
            $this->call('migrate', ['--step' => true, '--force' => true]);
        } else {
            $this->call('db:refresh');
        }

        $this->call('google-fonts:fetch');
        $this->call('storage:link');
        $this->call('cache:clear');
        $this->call('view:clear');
        $this->call('optimize');
        $this->call('event:cache');

        return 0;
    }
}
