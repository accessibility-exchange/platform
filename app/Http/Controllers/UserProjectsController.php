<?php

namespace App\Http\Controllers;

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

        if ($user->organization) {
            $projectable = $user->organization;
            if ($projectable->isParticipant() || $projectable->inProgressParticipatingProjects()->count()) {
                $section = 'participating';
            } else {
                $projectable->load('projects');
            }
        }

        if ($user->context === 'individual') {
            if ($user->individual->isParticipant() || $user->individual->inProgressParticipatingProjects()->count()) {
                $section = 'participating';
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

        if (in_array($user->context, ['individual', 'organization'])) {
            $userContext = $user->individual ?? $user->organization;
            if ($userContext && ($userContext->isConsultant() || $userContext->isConnector() || $userContext->inProgressContractedProjects()->count())) {
                return view('projects.my-projects', [
                    'user' => $user,
                    'projectable' => $user->organization ?? null,
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
            $userContext = $user->individual ?? $user->organization;
            if ($userContext && ($userContext->isParticipant() || $userContext->inProgressParticipatingProjects()->count())) {
                return view('projects.my-projects', [
                    'user' => $user,
                    'projectable' => $user->organization ?? null,
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
}
