<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
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
            return response('', 204);
        }

        return redirect()->intended($dashboard);
    }
}
