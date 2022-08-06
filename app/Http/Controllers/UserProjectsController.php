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

        if ($user->regulatedOrganization || $user->organization) {
            $projectable = $user->projectable();
            $projectable->load('projects');

            return view('projects.my-projects', [
                'user' => $user,
                'projectable' => $projectable,
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

        if ($user->organization && $user->organization->isConsultant() || $user->organization->isConnector()) {
            return view('projects.my-projects', [
                'user' => $user,
                'projectable' => $user->organization,
                'section' => 'contracted',
            ]);
        }

        if ($user->context === 'individual') {
            return view('projects.my-projects', [
                'user' => $user,
                'section' => 'contracted',
            ]);
        }

        abort(404);
    }

    public function showParticipating(): Response|View
    {
        $user = Auth::user();

        if ($user->organization && $user->organization->isParticipant()) {
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

        if ($user->organization) {
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

        abort(404);
    }
}
