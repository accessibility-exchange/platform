<?php

namespace App\Http\Controllers;

use App\Enums\UserContext;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserProjectsController extends Controller
{
    public function show(): Response|View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->context === UserContext::Organization->value && ! $user->organization) {
            return redirect(localized_route('organizations.show-type-selection'));
        }

        if ($user->context === UserContext::RegulatedOrganization->value && ! $user->regulatedOrganization) {
            return redirect(localized_route('regulated-organizations.show-type-selection'));
        }

        if ($user->regulated_organization) {
            $projectable = $user->regulated_organization;
            $projectable->load('projects');
        } elseif ($user->organization) {
            $projectable = $user->organization;
            if (! $projectable->isConsultant() && ! $projectable->isParticipant() && ! $projectable->isConnector()) {
                $projectable->load('projects');
            }
        }

        return view('projects.my-projects', [
            'user' => $user,
            'projectable' => $projectable ?? null,
        ]);
    }
}
