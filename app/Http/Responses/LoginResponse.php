<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Redirect to the appropriately localized dashboard for the logged-in user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toResponse($request)
    {
        $dashboard = \localized_route('dashboard', [], Auth::user()->locale);

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        return redirect()->intended($dashboard);
    }
}
