<?php

namespace App\Http\Controllers;

use App\Enums\UserContext;
use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\SaveUserContextRequest;
use App\Http\Requests\SaveUserDetailsRequest;
use App\Http\Requests\SaveUserLanguagesRequest;
use App\Http\Requests\UpdateUserIntroductionStatusRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use function localized_route;

class UserController extends Controller
{
    public function saveLanguages(SaveUserLanguagesRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('locale', $data['locale']);

        if (isset($data['invitation'])) {
            session()->put('invitation', $data['invitation']);
        }

        if (isset($data['context'])) {
            session()->put('context', $data['context']);
        }

        if (isset($data['email'])) {
            session()->put('email', $data['email']);
        }

        if (isset($data['role'])) {
            session()->put('invited_role', $data['role']);
        }

        return redirect(localized_route('register', ['step' => 2]));
    }

    /**
     * Show an introduction page for the logged-in user.
     *
     * @return View
     */
    public function showIntroduction(): View
    {
        $user = Auth::user();

        $skipTo = match ($user->context) {
            'individual' => localized_route('individuals.show-role-selection'),
            'organization' => $user->extra_attributes->get('invitation') ? localized_route('dashboard') : localized_route('organizations.show-type-selection'),
            'regulated-organization' => $user->extra_attributes->get('invitation') ? localized_route('dashboard') : localized_route('regulated-organizations.show-type-selection'),
            default => localized_route('dashboard'),
        };

        return view('users.show-introduction', [
            'user' => $user,
            'skipTo' => $skipTo,
        ]);
    }

    /**
     * Update the logged-in user's introduction status.
     *
     * @param  UpdateUserIntroductionStatusRequest  $request
     * @return RedirectResponse
     */
    public function updateIntroductionStatus(UpdateUserIntroductionStatusRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = Auth::user();

        $user->fill($data);
        $user->save();

        $redirectTo = match (Auth::user()->context) {
            'individual' => localized_route('individuals.show-role-selection'),
            'organization' => $user->extra_attributes->get('invitation') ? localized_route('dashboard') : localized_route('organizations.show-type-selection'),
            'regulated-organization' => $user->extra_attributes->get('invitation') ? localized_route('dashboard') : localized_route('regulated-organizations.show-type-selection'),
            default => localized_route('dashboard'),
        };

        return redirect($redirectTo);
    }

    /**
     * Show the dashboard view for the logged-in user.
     *
     * @return View
     */
    public function dashboard(): View
    {
        $user = Auth::user();

        $memberable = match ($user->context) {
            'regulated-organization' => $user->regulatedOrganization ?? null,
            'organization' => $user->organization ?? null,
            default => null,
        };

        $teamInvitation = $user->teamInvitation() ?? null;

        return view('dashboard', [
            'user' => $user,
            'memberable' => $memberable,
            'teamInvitation' => $teamInvitation,
            'teamInvitationable' => ! is_null($teamInvitation) ? $teamInvitation->invitationable : null,
            'teamAcceptUrl' => $teamInvitation ? URL::signedRoute('invitations.accept', $user->teamInvitation()) : null,
        ]);
    }

    public function notifications(): View
    {
        $user = Auth::user();

        return view('dashboard.notifications', [
            'user' => $user,
            'notifications' => $user->allUnreadNotifications(),
            'unreadCount' => $user->allUnreadNotifications()->count(),
        ]);
    }

    public function allNotifications(): View
    {
        $user = Auth::user();

        return view('dashboard.notifications', [
            'user' => $user,
            'notifications' => $user->allNotifications(),
            'unreadCount' => $user->allUnreadNotifications()->count(),
        ]);
    }

    public function trainings(): View
    {
        $user = Auth::user();
        $courses = $user->courses;
        $inProgressCourses = [];
        $completedCourses = [];

        foreach ($courses as $course) {
            if ($course?->getRelationValue('pivot')->received_certificate_at) {
                $completedCourses[] = $course;
            } else {
                $inProgressCourses[] = $course;
            }
        }

        return view('dashboard.trainings', [
            'user' => $user,
            'inProgressCourses' => $inProgressCourses,
            'completedCourses' => $completedCourses,
        ]);
    }

    /**
     * Store a new user's context in the session.
     *
     * @param  SaveUserContextRequest  $request
     * @return RedirectResponse
     */
    public function saveContext(SaveUserContextRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('context', $data['context']);
        session()->put('isNewOrganizationContext', empty(session()->get('invitation')) && (UserContext::Organization->value === $data['context'] || UserContext::RegulatedOrganization->value === $data['context']));

        return redirect(localized_route('register', ['step' => 3]));
    }

    /**
     * Store a new user's details in the session.
     *
     * @param  SaveUserDetailsRequest  $request
     * @return RedirectResponse
     */
    public function saveDetails(SaveUserDetailsRequest $request): RedirectResponse
    {
        $data = $request->validated();

        session()->put('name', $data['name']);
        session()->put('email', $data['email']);

        return redirect(localized_route('register', ['step' => 4]));
    }

    /**
     * Destroy a given user.
     *
     * @param  DestroyUserRequest  $request
     * @return RedirectResponse
     */
    public function destroy(DestroyUserRequest $request)
    {
        $user = $request->user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $user->delete();

        flash(__('hearth::user.destroy_succeeded'), 'success');

        return redirect(localized_route('welcome'));
    }
}
