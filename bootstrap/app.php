<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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

        $middleware->preventRequestsDuringMaintenance(except: [
            '/status/db',
            '/status',
        ]);

        $middleware->redirectGuestsTo(fn () => localized_route('login'));

        $middleware->throttleApi();

        $middleware->trimStrings(except: [
            'password',
            'password_confirmation',
        ]);

        $middleware->trustHosts(at: fn () => config('app.url'), subdomains: true);

        $middleware->trustProxies(at: '*');

        $middleware->prependToGroup('web', [
            \ChinLeung\MultilingualRoutes\DetectRequestLocale::class,
        ]);

        $middleware->appendToGroup('web', [
            \RalphJSmit\Livewire\Urls\Middleware\LivewireUrlsMiddleware::class,
            \App\Http\Middleware\ConfirmLanguage::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'password.confirm' => \App\Http\Middleware\RequirePassword::class,
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'localize' => \App\Http\Middleware\RedirectToPreferredLocale::class,
            'onboard' => \App\Http\Middleware\RedirectForOnboarding::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
