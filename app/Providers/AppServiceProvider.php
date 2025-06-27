<?php

namespace App\Providers;

use App\Enums\UserContext;
use App\Models\Engagement;
use App\Models\Individual;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Observers\EngagementObserver;
use App\Observers\UserObserver;
use App\Statuses\EngagementStatus;
use App\Statuses\IndividualStatus;
use App\Statuses\OrganizationStatus;
use App\Statuses\ProjectStatus;
use App\Statuses\RegulatedOrganizationStatus;
use App\Statuses\UserStatus;
use Blade;
use Composer\InstalledVersions;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Pulse\Facades\Pulse;
use Makeable\EloquentStatus\StatusManager;
use Ramsey\Uuid\Uuid;
use Spatie\LaravelIgnition\Facades\Flare;
use Spatie\Translatable\Facades\Translatable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if (config('app.env') !== 'local') {
            $url->forceScheme('https');
        }

        Blade::directive('theme', function () {
            return "<?php echo auth()->hasUser() ? auth()->user()->theme : Cookie::get('theme', 'system'); ?>";
        });

        Blade::directive('ariaDisabled', function () {
            return "<?php echo 'aria-disabled=\"true\" x-data @click.prevent data-label=\"'.__('not available yet').'\"'; ?>";
        });

        Filament::serving(function () {
            Filament::registerNavigationItems([
                NavigationItem::make(__('Dashboard'))
                    ->url(localized_route('dashboard'))
                    ->icon('heroicon-m-view-columns')
                    ->sort(-3),
                NavigationItem::make(__('Manage accounts'))
                    ->url(localized_route('admin.manage-accounts'))
                    ->icon('heroicon-s-users')
                    ->sort(-2),
                NavigationItem::make(__('Estimates and agreements'))
                    ->url(localized_route('admin.estimates-and-agreements'))
                    ->icon('heroicon-m-clipboard-document-check')
                    ->sort(-1),
            ]);
        });

        Flare::determineVersionUsing(function () {
            return InstalledVersions::getRootPackage()['pretty_version'];
        });

        Gate::define('viewPulse', function (User $user) {
            return $user->isAdministrator();
        });

        Pulse::user(fn ($user) => [
            'name' => Uuid::uuid5(config('app.id_namespace'), $user->id),
            'extra' => UserContext::labels()[$user->context],
        ]);

        StatusManager::bind(Engagement::class, EngagementStatus::class);
        StatusManager::bind(Individual::class, IndividualStatus::class);
        StatusManager::bind(Organization::class, OrganizationStatus::class);
        StatusManager::bind(RegulatedOrganization::class, RegulatedOrganizationStatus::class);
        StatusManager::bind(Project::class, ProjectStatus::class);
        StatusManager::bind(User::class, UserStatus::class);
        Translatable::fallback(fallbackLocale: 'en', fallbackAny: true, missingKeyCallback: function (
            Model $model,
            string $translationKey,
            string $locale,
            string $fallbackTranslation,
            string $fallbackLocale
        ) {
            // Handles fallback of sign language to equivalent written language
            $writtenLocale = to_written_language($locale);
            // Ignoring the next line for static analysis because it doesn't know that $model will only be types
            // that have the HasTranslatable trait.
            // @phpstan-ignore-next-line
            $writtenTranslation = $model->getTranslationWithoutFallback($translationKey, $writtenLocale);

            return ! empty($writtenTranslation) ? $writtenTranslation : $fallbackTranslation;
        });
        Engagement::observe(EngagementObserver::class);
        User::observe(UserObserver::class);

        $this->bootAuth();
    }

    public function bootAuth(): void
    {
        Auth::provider('encryptedUserProvider', function ($app, array $config) {
            return new EncryptedUserProvider($app['hash'], $config['model']);
        });

        Gate::define('block', function (User $user) {
            return config('app.features.blocking') && $user->context === 'individual'
                ? Response::allow()
                : Response::deny(__('You cannot block individuals or organizations.'));
        });

        Gate::define('receiveNotifications', function (User $user) {
            return $user->context === 'individual'
                ? Response::allow()
                : Response::deny(__('You cannot receive notifications about regulated or community organizations.'));
        });

        Password::defaults(function () {
            return Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised();
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject(__('Verify Email Address'))
                ->line(__('Please click the button below to verify your email address.'))
                ->action(__('Verify Email Address'), $url)
                ->line(__('If you did not create an account, no further action is required.'));
        });
    }
}
