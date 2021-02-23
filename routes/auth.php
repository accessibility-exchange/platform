<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::multilingual('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::multilingual('/register', [RegisteredUserController::class, 'store'])
    ->method('post')
    ->middleware('guest');

Route::multilingual('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::multilingual('/login', [AuthenticatedSessionController::class, 'store'])
    ->method('post')
    ->middleware('guest');

Route::multilingual('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::multilingual('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->method('post')
    ->middleware('guest')
    ->name('password.email');

Route::multilingual('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::multilingual('/reset-password', [NewPasswordController::class, 'store'])
    ->method('post')
    ->middleware('guest')
    ->name('password.update');

Route::multilingual('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::multilingual('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::multilingual('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->method('post')
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::multilingual('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::multilingual('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->method('post')
    ->middleware('auth');

Route::multilingual('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->method('post')
    ->middleware('auth')
    ->name('logout');
