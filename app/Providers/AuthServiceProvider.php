<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Entity' => 'App\Policies\EntityPolicy',
        'App\Models\Collection' => 'App\Policies\CollectionPolicy',
        'App\Models\Consultant' => 'App\Policies\ConsultantPolicy',
        'App\Models\Project' => 'App\Policies\ProjectPolicy',
        'App\Models\Organization' => 'App\Policies\OrganizationPolicy',
        'App\Models\Resource' => 'App\Policies\ResourcePolicy',
        'App\Models\Review' => 'App\Policies\ReviewPolicy',
        'App\Models\Story' => 'App\Policies\StoryPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
