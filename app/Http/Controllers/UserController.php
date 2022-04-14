<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\SaveUserContextRequest;
use App\Http\Requests\SaveUserDetailsRequest;
use App\Http\Requests\SaveUserLanguagesRequest;
use App\Http\Requests\UpdateUserDisplayPreferencesRequest;
use App\Http\Requests\UpdateUserIntroductionStatusRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('localize')->only('edit');
    }

    /**
     * Show an introduction page for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function showIntroduction(): View
    {
        return view('users.show-introduction', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the logged-in user's introduction status.
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateIntroductionStatus(UpdateUserIntroductionStatusRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Auth::user()->fill($data);
        Auth::user()->save();

        return redirect(\localized_route('users.show-role-selection'));
    }

    /**
     * Show a role selection page for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function showRoleSelection(): View
    {
        return view('users.show-role-selection', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Show the dashboard view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard(): View
    {
        return view('dashboard', [
            'currentUser' => Auth::user(),
        ]);
    }

    /**
     * Show the settings page for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function settings(): View
    {
        return view('users.settings', ['user' => Auth::user()]);
    }

    /**
     * Show the profile edit view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function edit(): View
    {
        return view('users.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Store a new user's context in the session.
     *
     * @param  \App\Http\Requests\SaveUserContextRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveContext(SaveUserContextRequest $request): RedirectResponse
    {
        $data = $request->validated();
        session()->put('context', $data['context']);

        return redirect(\localized_route('register', ['step' => 3]));
    }

    /**
     * Store a new user's details in the session.
     *
     * @param  \App\Http\Requests\SaveUserDetailsRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveDetails(SaveUserDetailsRequest $request): RedirectResponse
    {
        $data = $request->validated();
        session()->put('name', $data['name']);
        session()->put('email', $data['email']);

        return redirect(\localized_route('register', ['step' => 4]));
    }

    /**
     * Store a new user's language preferences in the session.
     *
     * @param  \App\Http\Requests\SaveUserLanguagesRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveLanguages(SaveUserLanguagesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        session()->put('locale', $data['locale']);
        if ($data['signed_language']) {
            session()->put('signed_language', $data['signed_language']);
        }

        return redirect(\localized_route('register', ['step' => 2]));
    }

    /**
     * Show the roles and permissions edit view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function editRolesAndPermissions(): View
    {
        return view('users.roles-and-permissions', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Show the display preferences edit view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function editDisplayPreferences(): View
    {
        $themes = [];

        foreach (config('themes') as $theme) {
            $themes[$theme] = __('themes.' . $theme);
        }

        return view('users.display-preferences', [
            'user' => Auth::user(),
            'themes' => $themes,
        ]);
    }

    /**
     * Show the display preferences edit view for the logged-in user.
     *
     * @param  \App\Http\Requests\UpdateUserDisplayPreferencesRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDisplayPreferences(UpdateUserDisplayPreferencesRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Auth::user()->fill($data);
        Auth::user()->save();

        flash(__('Your display preferences have been updated.'), 'success');

        Cookie::queue('theme', $data['theme']);

        return redirect(\localized_route('users.edit_display_preferences'));
    }

    /**
     * Show the notification preferences edit view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function editNotificationPreferences(): View
    {
        return view('users.notification-preferences', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Show the password and security admin view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function admin(): View
    {
        return view('users.admin', ['user' => Auth::user()]);
    }

    /**
     * Show the my projects view for the logged-in user.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showMyProjects(): mixed
    {
        if (Auth::user()->regulatedOrganization()) {
            $regulatedOrganization = Auth::user()->regulatedOrganization();
            $regulatedOrganization->load('pastProjects', 'currentProjects');

            return view('regulated-organizations.my-projects', [
                'regulatedOrganization' => Auth::user()->regulatedOrganization(),
            ]);
        }

        return redirect(\localized_route('dashboard'));
    }

    /**
     * Show the account deletion view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function delete(): View
    {
        return view('users.delete', ['user' => Auth::user()]);
    }

    /**
     * Destroy a given user.
     *
     * @param  \App\Http\Requests\DestroyUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyUserRequest $request)
    {
        $user = $request->user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $user->delete();

        flash(__('hearth::user.destroy_succeeded'), 'success');

        return redirect(\localized_route('welcome'));
    }
}
