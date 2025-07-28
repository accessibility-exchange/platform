<?php

namespace App\Http\Middleware;

use App\Enums\UserContext;
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

        if ($user->context === UserContext::RegulatedOrganization->value && ! $user->regulatedOrganization && $user->extra_attributes->get('invitation')) {
            return $next($request);
        }

        if ($user->context === UserContext::RegulatedOrganization->value && ! $user->regulatedOrganization) {
            return redirect(localized_route('regulated-organizations.show-type-selection'));
        }

        if ($user->context === UserContext::Organization->value && ! $user->organization && $user->extra_attributes->get('invitation')) {
            return $next($request);
        }

        if ($user->context === UserContext::Organization->value && ! $user->organization) {
            return redirect(localized_route('organizations.show-type-selection'));
        }

        if ($user->organization && empty($user->organization->roles)) {
            return redirect(localized_route('organizations.show-role-selection', $user->organization));
        }

        return $next($request);
    }
}
