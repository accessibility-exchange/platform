<?php

namespace App\Http\Controllers;

use App\Enums\ProjectInvolvement;
use App\Enums\UserContext;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserProjectsController extends Controller
{
    public function show(): Response|View
    {
        $user = Auth::user();

        if ($user->regulated_organization) {
            $projectable = $user->regulated_organization;
            $projectable->load('projects');
        }

        if ($this->isIndividualOrOrganizationUser($user)) {
            if ($this->isParticipant($user)) {
                $section = ProjectInvolvement::Participating->value;
            } elseif ($this->isContractor($user)) {
                $section = ProjectInvolvement::Contracted->value;
            }
        }

        if ($user->organization) {
            $projectable = $user->organization;
            if (! $projectable->isConsultant() && ! $projectable->isParticipant() && ! $projectable->isConnector()) {
                $projectable->load('projects');
            }
        }

        return view('projects.my-projects', [
            'user' => $user,
            'projectable' => $projectable ?? null,
            'section' => $section ?? null,
        ]);
    }

    public function showContracted(): Response|View
    {
        $user = Auth::user();

        if ($this->isIndividualOrOrganizationUser($user)) {
            if ($this->isContractor($user)) {
                return view('projects.my-projects', [
                    'user' => $user,
                    'projectable' => $user->organization,
                    'section' => 'contracted',
                ]);
            }
        }

        abort(404);
    }

    public function showParticipating(): Response|View
    {
        $user = Auth::user();

        if ($this->isIndividualOrOrganizationUser($user)) {
            if ($this->isParticipant($user)) {
                return view('projects.my-projects', [
                    'user' => $user,
                    'projectable' => $user->organization,
                    'section' => 'participating',
                ]);
            }
        }

        abort(404);
    }

    public function showRunning(): Response|View
    {
        $user = Auth::user();

        if (in_array($user->context, [UserContext::Organization->value, UserContext::RegulatedOrganization->value])) {
            return view('projects.my-projects', [
                'user' => $user,
                'projectable' => $user->regulated_organization ?? $user->organization,
                'section' => ProjectInvolvement::Running->value,
            ]);
        }

        abort(404);
    }

    public function isParticipant(User $user): bool
    {
        $userContext = $user->{$user->context};

        return $userContext && ($userContext->isParticipant() || $userContext->inProgressParticipatingProjects()->count());
    }

    public function isContractor(User $user): bool
    {
        $userContext = $user->{$user->context};

        return $userContext && ($userContext->isConsultant() || $userContext->isConnector() || $userContext->inProgressContractedProjects()->count());
    }

    public function isIndividualOrOrganizationUser(User $user): bool
    {
        return in_array($user->context, [UserContext::Individual->value, UserContext::Organization->value]);
    }
}
