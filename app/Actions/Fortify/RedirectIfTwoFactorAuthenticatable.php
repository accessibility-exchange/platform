<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as RedirectIfTwoFactorAuthenticatableAction;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfTwoFactorAuthenticatable extends RedirectIfTwoFactorAuthenticatableAction
{
    /**
     * Get the two factor authentication enabled response.
     *
     * @param  Request  $request
     * @param  mixed  $user
     * @return Response
     */
    protected function twoFactorChallengeResponse($request, $user)
    {
        $request->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $request->filled('remember'),
        ]);

        return $request->wantsJson()
                    ? response()->json(['two_factor' => true])
                    : redirect()->route($user->locale.'.two-factor.login');
    }
}
