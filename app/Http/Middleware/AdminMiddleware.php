<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (Auth::user() && Auth::user()->isAdministrator()) {
            return $next($request);
        }

        return redirect(Auth::user() ? localized_route('dashboard') : localized_route('welcome'));
    }
}
