<?php

namespace App\Providers;

use App\Models\Engagement;
use App\Models\Individual;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Observers\EngagementObserver;
use App\Observers\UserObserver;
use App\Settings;
use App\Statuses\EngagementStatus;
use App\Statuses\IndividualStatus;
use App\Statuses\OrganizationStatus;
use App\Statuses\ProjectStatus;
use App\Statuses\RegulatedOrganizationStatus;
use App\Statuses\UserStatus;
use Blade;
use Composer\InstalledVersions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Makeable\EloquentStatus\StatusManager;
use Reworck\FilamentSettings\FilamentSettings;
use Spatie\LaravelIgnition\Facades\Flare;
use Spatie\Translatable\Facades\Translatable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Settings::class, function () {
            return Settings::make(storage_path('app/settings.json'));
        });
    }

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
            Filament::registerViteTheme('resources/css/filament.css');
            Filament::registerNavigationItems([
                NavigationItem::make(__('Dashboard'))
                    ->url(localized_route('dashboard'))
                    ->icon('heroicon-s-view-boards')
                    ->sort(-3),
                NavigationItem::make(__('Manage accounts'))
                    ->url(localized_route('admin.manage-accounts'))
                    ->icon('heroicon-s-users')
                    ->sort(-2),
                NavigationItem::make(__('Estimates and agreements'))
                    ->url(localized_route('admin.estimates-and-agreements'))
                    ->icon('heroicon-s-clipboard-check')
                    ->sort(-1),
            ]);

            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('Training'),
            ]);
        });

        Flare::determineVersionUsing(function () {
            return InstalledVersions::getRootPackage()['pretty_version'];
        });

        StatusManager::bind(Engagement::class, EngagementStatus::class);
        StatusManager::bind(Individual::class, IndividualStatus::class);
        StatusManager::bind(Organization::class, OrganizationStatus::class);
        StatusManager::bind(RegulatedOrganization::class, RegulatedOrganizationStatus::class);
        StatusManager::bind(Project::class, ProjectStatus::class);
        StatusManager::bind(User::class, UserStatus::class);
        Translatable::fallback(fallbackLocale: 'en', fallbackAny: true);
        Engagement::observe(EngagementObserver::class);
        User::observe(UserObserver::class);

        FilamentSettings::setFormFields([
            TextInput::make('email')
                ->label(__('Support email'))
                ->default('support@accessibilityexchange.ca')
                ->required()
                ->email(),
            TextInput::make('phone')
                ->label(__('Support phone'))
                ->default('1 (888) 867-0053')
                ->required(),
            Textarea::make('address')
                ->label(__('Mailing address'))
                ->default("The Accessibility Exchange ℅ IRIS  \n1 University Avenue, 3rd Floor  \nToronto, ON M5J 2P1")
                ->required(),
            TextInput::make('facebook')
                ->label(__('Facebook page'))
                ->default('https://facebook.com/AccessXchange')
                ->required()
                ->activeUrl(),
            TextInput::make('linkedin')
                ->label(__('LinkedIn page'))
                ->default('https://linkedin.com/company/the-accessibility-exchange/')
                ->required()
                ->activeUrl(),
            TextInput::make('twitter')
                ->label(__('Twitter page'))
                ->default('https://twitter.com/AccessXchange')
                ->required()
                ->activeUrl(),
            TextInput::make('youtube')
                ->label(__('YouTube page'))
                ->default('https://www.youtube.com/channel/UC-mIk4Xk04wF4urFSKZQOAA')
                ->required()
                ->activeUrl(),
        ]);
    }
}
