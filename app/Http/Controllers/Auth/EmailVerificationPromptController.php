<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $url = LaravelLocalization::getLocalizedURL($request->user()->pluck('locale')->first(), RouteServiceProvider::HOME);

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended($url)
                    : view('auth.verify-email');
    }
}
