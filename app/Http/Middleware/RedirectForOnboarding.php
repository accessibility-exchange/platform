<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RedirectForOnboarding
{
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $user = Auth::user();

        if ($user->context === 'individual' && empty($user->individual->roles)) {
            return redirect(localized_route('individuals.show-role-selection'));
        }

        if ($user->context === 'regulated-organization' && ! $user->regulatedOrganization && $user->extra_attributes->get('invitation')) {
            return $next($request);
        }

        if ($user->context === 'regulated-organization' && ! $user->regulatedOrganization) {
            return redirect(localized_route('regulated-organizations.show-type-selection'));
        }

        if ($user->context === 'organization' && ! $user->organization && $user->extra_attributes->get('invitation')) {
            return $next($request);
        }

        if ($user->context === 'organization' && ! $user->organization) {
            return redirect(localized_route('organizations.show-type-selection'));
        }

        if ($user->organization && empty($user->organization->roles)) {
            return redirect(localized_route('organizations.show-role-selection', $user->organization));
        }

        return $next($request);
    }
}
