<?php namespace App\Actions\Fortify;

use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as RedirectIfTwoFactorAuthenticatableAction;

class RedirectIfTwoFactorAuthenticatable extends RedirectIfTwoFactorAuthenticatableAction
{
    /**
     * Get the two factor authentication enabled response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function twoFactorChallengeResponse($request, $user)
    {
        $request->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $request->filled('remember'),
        ]);

        return $request->wantsJson()
                    ? response()->json(['two_factor' => true])
                    : redirect()->route($user->locale . '.two-factor.login');
    }
}
