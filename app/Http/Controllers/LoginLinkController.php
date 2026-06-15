<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Spatie\LoginLink\Http\Controllers\LoginLinkController as SpatieLoginLinkController;
use Spatie\LoginLink\Http\Requests\LoginLinkRequest;

class LoginLinkController extends SpatieLoginLinkController
{
    public function __invoke(LoginLinkRequest $request)
    {
        $this->ensureAllowedEnvironment();

        $this->ensureAllowedHost($request);

        $authenticatable = $this->getAuthenticatable($request);

        $this->performLogin($request->guard, $authenticatable);

        Cookie::queue('theme', Auth::user()->theme);

        $redirectUrl = $this->getRedirectUrl($request);

        return redirect()->to($redirectUrl);
    }
}
