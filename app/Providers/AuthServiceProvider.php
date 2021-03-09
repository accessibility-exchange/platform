<?php

namespace App\Providers;

use App\Models\Profile;
use App\Models\Organization;
use App\Models\User;
use App\Policies\ProfilePolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Profile' => 'App\Policies\ProfilePolicy',
        'App\Models\Organization' => 'App\Policies\OrganizationPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
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
