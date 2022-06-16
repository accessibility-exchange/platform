<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RedirectForOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $user = Auth::user();

        if ($user->context === 'regulated-organization' && ! $user->regulatedOrganization) {
            return redirect(localized_route('regulated-organizations.show-type-selection'));
        }

        if ($user->context === 'organization' && ! $user->organization) {
            return redirect(localized_route('organizations.show-type-selection'));
        }

        return $next($request);
    }
}
