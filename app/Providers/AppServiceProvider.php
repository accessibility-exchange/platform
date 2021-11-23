<?php

namespace App\Providers;

use App\Models\Consultant;
use App\Models\Project;
use App\Settings;
use App\Statuses\ConsultantStatus;
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

        StatusManager::bind(Consultant::class, ConsultantStatus::class);
        StatusManager::bind(Project::class, ProjectStatus::class);
    }
}
