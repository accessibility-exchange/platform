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
            if ($projectable->isConsultant() || $projectable->isConnector()) {
                $section = 'contracted';
            } elseif ($projectable->isParticipant()) {
                $section = 'participating';
            } else {
                $projectable->load('projects');
            }
        }

        if ($user->context === 'individual') {
            if (! $user->individual->isParticipant()) {
                $section = 'contracted';
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
            if ($user->individual && $user->individual->isParticipant() && ($user->individual->isConsultant() || $user->individual->isConnector())) {
                return view('projects.my-projects', [
                    'user' => $user,
                    'section' => 'contracted',
                ]);
            }
        }

        abort(404);
    }

    public function showParticipating(): Response|View
    {
        $user = Auth::user();

        if ($user->context === 'organization' && $user->organization && $user->organization->isParticipant() && ($user->organization->isConsultant() || $user->organization->isConnector())) {
            return view('projects.my-projects', [
                'user' => $user,
                'projectable' => $user->organization,
                'section' => 'participating',
            ]);
        }

        abort(404);
    }

    public function showRunning(): Response|View
    {
        $user = Auth::user();

        if ($user->context === 'organization') {
            if ($user->organization && ($user->organization->isParticipant() || $user->organization->isConsultant() || $user->organization->isConnector())) {
                return view('projects.my-projects', [
                    'user' => $user,
                    'projectable' => $user->organization,
                    'section' => 'running',
                ]);
            }
        }

        abort(404);
    }
}
