<?php

namespace App\Providers;

use App\Models\CommunityMember;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\User;
use App\Observers\UserObserver;
use App\Settings;
use App\Statuses\CommunityMemberStatus;
use App\Statuses\ProjectStatus;
use App\Statuses\RegulatedOrganizationStatus;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Makeable\EloquentStatus\StatusManager;
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

        Collection::macro('prepareForForm', function () {
            /** @var Collection $this */
            return $this->mapWithKeys(function ($item) {
                return [
                    $item->id => [
                        'label' => $item->name,
                        'hint' => $item->description,
                    ],
                ];
            })->toArray();
        });

        StatusManager::bind(CommunityMember::class, CommunityMemberStatus::class);
        StatusManager::bind(RegulatedOrganization::class, RegulatedOrganizationStatus::class);
        StatusManager::bind(Project::class, ProjectStatus::class);
        Translatable::fallback(fallbackLocale: 'en');
        User::observe(UserObserver::class);
    }
}
