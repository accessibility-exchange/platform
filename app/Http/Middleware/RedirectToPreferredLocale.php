<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectToPreferredLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->cookie('locale');

        if (
            $locale
            && in_array($locale, \locales())
            && $locale !== $request->segment(1)
        ) {
            return redirect(\current_route($locale));
        }

        return $next($request);
    }
}
