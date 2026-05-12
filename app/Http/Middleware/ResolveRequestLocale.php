<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Livewire\LivewireManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Persist and restore the application locale across Livewire requests.
 *
 * This middleware stores the locale in the session on page requests and
 * restores it on Livewire requests.
 *
 * @see https://github.com/spatie/laravel-sluggable/discussions/228
 * @see https://github.com/chinleung/laravel-multilingual-routes/issues/70
 * @see https://github.com/accessibility-exchange/platform/issues/3037
 */
class ResolveRequestLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app(LivewireManager::class)->isLivewireRequest()) {
            $locale = session('locale');

            if ($locale && in_array($locale, locales(), false)) {
                app()->setLocale($locale);
            }
        } else {
            session(['locale' => app()->getLocale()]);
        }

        return $next($request);
    }
}
