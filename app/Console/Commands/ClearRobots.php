<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearRobots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:clear-robots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the robots.txt file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Storage::disk('public')->delete('robots.txt');
    }
}
