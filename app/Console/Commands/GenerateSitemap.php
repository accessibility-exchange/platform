<?php

namespace App\Console\Commands;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'seo:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = ['/' => ['en' => 'en', 'asl' => 'asl', 'fr' => 'fr', 'lsq' => 'lsq']];
        // once deployed to the server, files have the same modified date, use README as a default last modified date
        $lastmod = ['default' => Carbon::createFromTimestamp(filemtime('./README.md'))->toISOString()];
        $hasPages = Schema::hasTable('pages'); // Check if the Pages table exists.
        foreach (Route::getRoutes()->get('GET') as $route) {
            if ($route->named(config('seo.sitemap.patterns'))) {
                $routeURI = $route->uri();
                [$locale, $url] = explode('/', $routeURI, 2);
                if (array_key_exists($url, $routes)) {
                    $routes[$url][$locale] = $routeURI;
                } else {
                    $routes[$url] = [$locale => $routeURI];
                    if ($route->named(config('seo.sitemap.pages')) && $hasPages) {
                        $routeName = explode('.', $route->getName());
                        /*
                         * TODO: come up with better query for the page, as slug may not follow the pattern in route name
                         * or changes to the slug would break this logic: https://github.com/accessibility-exchange/platform/issues/1973
                         */
                        $page = Page::firstWhere('slug->en', '=', end($routeName));
                        $lastmod[$url] = $page?->updated_at?->toISOString();
                    }
                }
            }
        }

        Storage::disk('public')->put('sitemap.xml', view('sitemap', ['routes' => $routes, 'lastmod' => $lastmod])->render());
    }
}
