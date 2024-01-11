<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:clear-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Storage::disk('public')->delete('sitemap.xml');
    }
}
