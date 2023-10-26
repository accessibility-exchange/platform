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
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Makeable\EloquentStatus\StatusManager;
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

        StatusManager::bind(Engagement::class, EngagementStatus::class);
        StatusManager::bind(Individual::class, IndividualStatus::class);
        StatusManager::bind(Organization::class, OrganizationStatus::class);
        StatusManager::bind(RegulatedOrganization::class, RegulatedOrganizationStatus::class);
        StatusManager::bind(Project::class, ProjectStatus::class);
        StatusManager::bind(User::class, UserStatus::class);
        Translatable::fallback(fallbackLocale: 'en', fallbackAny: true);
        Engagement::observe(EngagementObserver::class);
        User::observe(UserObserver::class);
    }
}
