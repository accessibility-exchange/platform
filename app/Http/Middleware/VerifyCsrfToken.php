<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Auth;

class VerifyCsrfToken extends Middleware
{
    protected $except = [];

    public function handle($request, \Closure $next): mixed
    {
        if ($request->route()->named(['en.logout', 'fr.logout'])) {
            if (! Auth::check() || Auth::guard()->viaRemember()) {
                $this->except[] = localized_route('logout');
            }
        }

        return parent::handle($request, $next);
    }
}
