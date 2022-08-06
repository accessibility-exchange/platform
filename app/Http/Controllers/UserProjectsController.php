<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserProjectsController extends Controller
{
    public function show(): RedirectResponse|View
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

        return redirect(localized_route('dashboard'));
    }

    public function showContracted(): RedirectResponse|View
    {
        $user = Auth::user();

        if ($user->organization) {
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

        return redirect(localized_route('dashboard'));
    }

    public function showParticipating(): RedirectResponse|View
    {
        $user = Auth::user();

        if ($user->organization) {
            return view('projects.my-projects', [
                'user' => $user,
                'projectable' => $user->organization,
                'section' => 'participating',
            ]);
        }

        return redirect(localized_route('dashboard'));
    }

    public function showRunning(): RedirectResponse|View
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

        return redirect(localized_route('dashboard'));
    }
}
