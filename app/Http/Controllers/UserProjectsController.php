<?php

namespace App\Http\Controllers;

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

        if (in_array($user->context, [UserContext::Individual->value, UserContext::Organization->value])) {
            if ($this->isParticipant($user)) {
                $section = 'participating';
            } elseif ($this->isContractor($user)) {
                $section = 'contracted';
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
            'projectable' => $projectable,
            'section' => $section,
        ]);
    }

    public function showContracted(): Response|View
    {
        $user = Auth::user();

        if (in_array($user->context, [UserContext::Individual->value, UserContext::Organization->value])) {
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

        if (in_array($user->context, ['individual', 'organization'])) {
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

        if (in_array($user->context, ['organization', 'regulated-organization'])) {
            return view('projects.my-projects', [
                'user' => $user,
                'projectable' => $user->regulated_organization ?? $user->organization,
                'section' => 'running',
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
}
