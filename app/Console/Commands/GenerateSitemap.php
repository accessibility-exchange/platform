<?php

namespace App\Console\Commands;

use App\Models\Page;
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
        $lastmod = ['default' => Carbon::createFromTimestamp(filemtime('./README.md'))->toISOString()];
        foreach (Route::getRoutes()->get('GET') as $route) {
            if ($route->named(config('seo.sitemap.patterns'))) {
                $routeURI = $route->uri();
                [$locale, $url] = explode('/', $routeURI, 2);
                if (array_key_exists($url, $routes)) {
                    $routes[$url][$locale] = $routeURI;
                } else {
                    $routes[$url] = [$locale => $routeURI];
                    $routeName = explode('.', $route->getName());
                    $page = Page::where('slug->en', '=', $routeName[count($routeName) - 1])->first();
                    if ($page) {
                        $lastmod[$url] = $page->updated_at->toISOString();
                    }
                }
            }
        }
        file_put_contents('./public/sitemap.xml', view('sitemap', ['routes' => $routes, 'lastmod' => $lastmod])->render());

        return 0;
    }
}
