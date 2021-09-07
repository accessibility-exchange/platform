<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Redirect to the appropriately localized home page for the logged-in user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toResponse($request)
    {
        $home = \localized_route('home', [], Auth::user()->locale);

        if ($request->wantsJson()) {
            return response('', 204);
        }

        return redirect()->intended($home);
    }
}
