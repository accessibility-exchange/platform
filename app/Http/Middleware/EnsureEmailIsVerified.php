<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return Response|RedirectResponse|null
     */
    public function handle(Request $request, Closure $next, string $redirectToRoute = null): Response|RedirectResponse|null
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail())) {
            return Redirect::guest(localized_route($redirectToRoute ?: 'verification.notice'));
        }

        return $next($request);
    }
}
