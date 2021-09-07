<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest;

class VerifyEmailController extends \Laravel\Fortify\Http\Controllers\VerifyEmailController
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Laravel\Fortify\Http\Requests\VerifyEmailRequest  $request
     * @phpstan-ignore-next-line
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(VerifyEmailRequest $request)
    {
        $home = \localized_route('home', ['verified' => 1], $request->user()->locale);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($home);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        flash(__('hearth::auth.verification_succeeded'), 'success');

        return redirect()->intended($home);
    }
}
