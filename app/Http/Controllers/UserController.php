<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List all users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    /**
     * Show the profile for a given user.
     *
     * @param  User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    /**
     * Show the edit view for a given user.
     *
     * @param  User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update a given user.
     *
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user)
    {
        $user->update(request()->validate([
            'name' => 'required',
            'locale' => 'required',
            'locality' => 'nullable',
            'region' => 'nullable',
            'about' => 'nullable'
        ]));

        return redirect($user->locale . '/people/' . $user->slug);
    }
}
