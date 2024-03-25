<?php

namespace App\Console\Commands;

use App\Jobs\BringSiteBackUp;
use Illuminate\Console\Command;

class MaintenanceModeQueueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put the site into maintenance mode and bring it back up after 5 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Put the site into maintenance mode
        $this->call('down');

        // Schedule bringing the site back up after 5 minutes
        BringSiteBackUp::dispatch()->delay(now()->addMinutes(5));

        $this->info('The site is in maintenance mode. It will be brought back up in 5 minutes.');
    }
}
