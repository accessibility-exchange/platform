<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the edit view for a given user.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('users.edit', ['user' => request()->user()]);
    }

    /**
     * Destroy a given user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        $user = Auth::user();

        Auth::guard('web')->logout();

        $user->delete();

        return redirect(localized_route('welcome'));
    }
}
