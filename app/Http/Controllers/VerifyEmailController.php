<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
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
        $dashboard = localized_route('dashboard', ['verified' => 1], $request->user()->locale);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($dashboard);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($dashboard)->withSuccess(__('auth.verification_completed'));
    }
}
