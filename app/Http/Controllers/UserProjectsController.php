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

        if ($user->regulatedOrganization) {
            $projectable = $user->projectable();
            $projectable->load('projects');

            return view('projects.my-projects', [
                'user' => $user,
                'projectable' => $projectable,
            ]);
        }

        $projectable = null;
        $section = null;

        if ($user->organization) {
            if ($user->organization->isConsultant() || $user->organization->isConnector()) {
                $section = 'contracted';
            } elseif ($user->organization->isParticipant()) {
                $section = 'participating';
            } else {
                $projectable = $user->projectable();
                $projectable->load('projects');
            }

            return view('projects.my-projects', [
                'user' => $user,
                'projectable' => $projectable,
                'section' => $section,
            ]);
        }

        if ($user->context === 'individual') {
            return view('projects.my-projects', [
                'user' => $user,
            ]);
        }

        abort(404);
    }

    public function showContracted(): Response|View
    {
        $user = Auth::user();

        if (in_array($user->context, ['individual', 'organization'])) {
            if ($user->individual && ($user->individual->isConsultant() || $user->individual->isConnector())) {
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

        if ($user->context === 'organization' && $user->organization && $user->organization->isParticipant()) {
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

        if (in_array($user->context, ['regulated-organization', 'organization'])) {
            if ($user->organization && ($user->organization->isParticipant() || $user->organization->isConsultant() || $user->organization->isConnector())) {
                return view('projects.my-projects', [
                    'user' => $user,
                    'projectable' => $user->organization,
                    'section' => 'running',
                ]);
            }

            if ($user->regulatedOrganization) {
                return view('projects.my-projects', [
                    'user' => $user,
                    'projectable' => $user->regulatedOrganization,
                    'section' => 'running',
                ]);
            }
        }

        abort(404);
    }
}
