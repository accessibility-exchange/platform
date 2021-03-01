<?php

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    $enableViews = config('fortify.views', true);

    if (Features::enabled(Features::registration())) {
        Route::multilingual('/register', [RegisteredUserController::class, 'create'])
            ->middleware('guest')
            ->name('register');

        Route::multilingual('/register', [RegisteredUserController::class, 'store'])
            ->method('post')
            ->middleware('guest');
    }

    if ($enableViews) {
        Route::multilingual('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware('guest')
            ->name('login');
    }

    $limiter = config('fortify.limiters.login');

    Route::multilingual('/login', [AuthenticatedSessionController::class, 'store'])
        ->method('post')
        ->middleware(array_filter([
            'guest',
            $limiter ? 'throttle:' . $limiter : null,
        ]));

    Route::multilingual('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->method('post')
        ->middleware('auth')
        ->name('logout');

    if (Features::enabled(Features::resetPasswords())) {
        if ($enableViews) {
            Route::multilingual('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');


            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');
        }

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest')
        ->name('password.email');

        Route::multilingual('/reset-password', [NewPasswordController::class, 'store'])
            ->method('post')
            ->middleware('guest')
            ->name('password.update');
    }

    if (Features::enabled(Features::emailVerification())) {
        if ($enableViews) {
            Route::multilingual('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth')
                ->name('verification.notice');
        }

        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['auth', 'signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');
    }

    if (Features::enabled(Features::updateProfileInformation())) {
        Route::multilingual('/user/profile', [ProfileInformationController::class, 'update'])
            ->method('put')
            ->middleware(['auth'])
            ->name('user-profile-information.update');
    }

    if (Features::enabled(Features::updatePasswords())) {
        Route::multilingual('/user/password', [PasswordController::class, 'update'])
            ->method('put')
            ->middleware(['auth'])
            ->name('user-password.update');
    }

    if ($enableViews) {
        Route::multilingual('/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->middleware('auth')
            ->name('password.confirm');
    }

    Route::multilingual('/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->method('post')
        ->middleware('auth');
});
