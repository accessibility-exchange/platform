<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\DestroyUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the profile edit view for the logged-in user.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('users.edit', ['user' => Auth::user()]);
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

        flash(__('user.destroy_succeeded'), 'success');

        return redirect(localized_route('welcome'));
    }
}
