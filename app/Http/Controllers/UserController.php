<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyUserRequest;
use App\Http\Requests\UpdateUserDisplayPreferencesRequest;
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
     * Show the account admin view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function admin(): View
    {
        return view('users.admin', ['user' => Auth::user()]);
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

        $user->delete();

        flash(__('hearth::user.destroy_succeeded'), 'success');


        return redirect(\localized_route('welcome'));
    }
}
