<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\ConfirmLanguage;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\RedirectForOnboarding;
use App\Http\Middleware\RedirectToPreferredLocale;
use App\Http\Middleware\RequirePassword;
use App\Http\Middleware\ResolveRequestLocale;
use ChinLeung\MultilingualRoutes\DetectRequestLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use RalphJSmit\Livewire\Urls\Middleware\LivewireUrlsMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->encryptCookies(except: [
            'theme',
        ]);

        $middleware->redirectGuestsTo(fn () => localized_route('login'));

        $middleware->throttleApi();

        $middleware->trimStrings(except: [
            'password',
            'password_confirmation',
        ]);

        $middleware->trustProxies(at: '*');

        $middleware->prependToGroup('web', [
            DetectRequestLocale::class,
        ]);

        $middleware->appendToGroup('web', [
            ResolveRequestLocale::class,
            LivewireUrlsMiddleware::class,
            ConfirmLanguage::class,
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'password.confirm' => RequirePassword::class,
            'verified' => EnsureEmailIsVerified::class,
            'localize' => RedirectToPreferredLocale::class,
            'onboard' => RedirectForOnboarding::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
