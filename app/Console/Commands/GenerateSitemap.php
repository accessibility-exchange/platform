<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

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
        $default_lastmod = Carbon::createFromTimestamp(filemtime('./README.md'))->toISOString();
        foreach (Route::getRoutes()->get('GET') as $route) {
            if ($route->named(config('seo.sitemap.patterns'))) {
                $routeURI = $route->uri();
                [$locale, $url] = explode('/', $routeURI, 2);
                if (array_key_exists($url, $routes)) {
                    $routes[$url][$locale] = $routeURI;
                } else {
                    $routes[$url] = [$locale => $routeURI];
                }
            }
        }
        file_put_contents('./public/sitemap.xml', view('sitemap', ['routes' => $routes, 'default_lastmod' => $default_lastmod])->render());

        return 0;
    }
}
