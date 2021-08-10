<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;

class PasswordResetResponse implements PasswordResetResponseContract
{
    /**
     * Redirect to the appropriately localized dashboard for the logged-in user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        return redirect()->intended(\localized_route('login'));
    }
}
