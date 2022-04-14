<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Redirect to the appropriately localized dashboard for the registered user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toResponse($request)
    {
        $introduction = \localized_route('users.show-introduction', [], Auth::user()->locale);

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        return redirect()->intended($introduction);
    }
}
