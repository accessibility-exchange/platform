<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateRobots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate-robots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the robots.txt file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Storage::disk('public')->put('robots.txt', view('robots')->render());
    }
}
