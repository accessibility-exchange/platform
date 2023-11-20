<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearSeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the robots.txt and sitemap files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('seo:clear-sitemap');
        $this->call('seo:clear-robots');
    }
}
