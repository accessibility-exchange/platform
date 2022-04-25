<?php

namespace App\Providers;

use App\Models\CommunityMember;
use App\Models\Project;
use App\Models\User;
use App\Observers\UserObserver;
use App\Settings;
use App\Statuses\CommunityMemberStatus;
use App\Statuses\ProjectStatus;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Makeable\EloquentStatus\StatusManager;

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

        StatusManager::bind(CommunityMember::class, CommunityMemberStatus::class);
        StatusManager::bind(Project::class, ProjectStatus::class);
        User::observe(UserObserver::class);
    }
}
