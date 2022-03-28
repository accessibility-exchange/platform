<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Http\Requests\VerifyEmailRequest;

class VerifyEmailController extends \Laravel\Fortify\Http\Controllers\VerifyEmailController
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Laravel\Fortify\Http\Requests\VerifyEmailRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(VerifyEmailRequest $request): RedirectResponse
    {
        $dashboard = \localized_route('dashboard', ['verified' => 1], $request->user()->locale);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($dashboard);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        flash(__('hearth::auth.verification_succeeded'), 'success');

        return redirect()->intended($dashboard);
    }
}
