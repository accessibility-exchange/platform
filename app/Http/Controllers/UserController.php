<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestroyUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
     * Show the profile edit view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $themes = [];

        foreach (config('themes') as $theme) {
            $themes[$theme] = __('themes.' . $theme);
        }

        return view('users.edit', [
            'user' => Auth::user(),
            'themes' => $themes,
        ]);
    }

    /**
     * Show the account admin view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function admin()
    {
        return view('users.admin', ['user' => Auth::user()]);
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

        return redirect(localized_route('welcome'));
    }
}
