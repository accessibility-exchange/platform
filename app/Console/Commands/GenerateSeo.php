<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateSeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the robots.txt and sitemap files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('seo:generate-sitemap');
        $this->call('seo:generate-robots');
    }
}
