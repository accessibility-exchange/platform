<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

class ConfirmLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->isRedirect()) {
            return $next($request);
        }

        if ($request->hasCookie('language-confirmed')) {
            $request->session()->put('language-confirmed', true);
        }

        Cookie::queue(Cookie::forever('language-confirmed', 'true'));

        return $next($request);
    }

    public function isRedirect(): bool
    {
        return (bool) filter_var(Route::current()->parameter('status'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 300, 'max_range' => 399]]);
    }
}
