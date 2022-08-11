<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\SaveUserContextRequest;
use App\Http\Requests\SaveUserDetailsRequest;
use App\Http\Requests\SaveUserLanguagesRequest;
use App\Http\Requests\UpdateUserIntroductionStatusRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use function localized_route;

class UserController extends Controller
{
    /**
     * Show an introduction page for the logged-in user.
     *
     * @return View
     */
    public function showIntroduction(): View
    {
        $skipTo = match (Auth::user()->context) {
            'individual' => localized_route('individuals.show-role-selection'),
            'regulated-organization' => localized_route('regulated-organizations.show-type-selection'),
            default => localized_route('dashboard'),
        };

        return view('users.show-introduction', [
            'user' => Auth::user(),
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

        Auth::user()->fill($data);
        Auth::user()->save();

        $redirectTo = match (Auth::user()->context) {
            'individual' => localized_route('individuals.show-role-selection'),
            'organization' => localized_route('organizations.show-type-selection'),
            'regulated-organization' => localized_route('regulated-organizations.show-type-selection'),
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

        return view('dashboard', [
            'user' => $user,
            'memberable' => $memberable,
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
     * Store a new user's language preferences in the session.
     *
     * @param  SaveUserLanguagesRequest  $request
     * @return RedirectResponse
     */
    public function saveLanguages(SaveUserLanguagesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        session()->put('locale', $data['locale']);
        if ($data['signed_language']) {
            session()->put('signed_language', $data['signed_language']);
        }

        return redirect(localized_route('register', ['step' => 2]));
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
