<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\RedirectIfTwoFactorAuthenticatable;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Enums\UserContext;
use App\Http\Responses\FailedTwoFactorLoginResponse;
use App\Http\Responses\FilamentLogoutResponse;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\PasswordResetResponse;
use App\Http\Responses\RegisterResponse;
use App\Http\Responses\TwoFactorLoginResponse;
use App\Traits\RetrievesUserByNormalizedEmail;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Spatie\LaravelOptions\Options;

class FortifyServiceProvider extends ServiceProvider
{
    use RetrievesUserByNormalizedEmail;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResponseBindings();

        Fortify::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::authenticateUsing(function (Request $request) {
            $user = $this->retrieveUserByEmail($request->email);

            if ($user &&
                Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(function () {
            return view('auth.register', [
                'contexts' => Options::forEnum(UserContext::class)
                    ->reject(fn (UserContext $context) => $context === UserContext::Administrator)
                    ->append(fn (UserContext $context) => [
                        'hint' => $context->description(),
                        'interpretation' => $context->interpretation(),
                    ])
                    ->toArray(),
            ]);
        });
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn () => view('auth.reset-password'));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));

        Fortify::authenticateThrough(function (Request $request) {
            return array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
                AttemptToAuthenticate::class,
                PrepareAuthenticatedSession::class,
            ]);
        });
    }

    /**
     * Register response bindings.
     */
    protected function registerResponseBindings(): void
    {
        $this->app->singleton(
            FailedTwoFactorLoginResponseContract::class,
            FailedTwoFactorLoginResponse::class
        );
        $this->app->singleton(
            LoginResponseContract::class,
            LoginResponse::class
        );
        $this->app->singleton(
            TwoFactorLoginResponseContract::class,
            TwoFactorLoginResponse::class
        );
        $this->app->singleton(
            RegisterResponseContract::class,
            RegisterResponse::class
        );
        $this->app->singleton(
            PasswordResetResponseContract::class,
            PasswordResetResponse::class
        );
        $this->app->singleton(
            LogoutResponse::class,
            FilamentLogoutResponse::class
        );
    }
}
