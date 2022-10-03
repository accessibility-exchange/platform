<?php

namespace App\Providers;

use App\Models\Engagement;
use App\Models\Individual;
use App\Models\Meeting;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Models\User;
use App\Policies\EngagementPolicy;
use App\Policies\IndividualPolicy;
use App\Policies\MeetingPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RegulatedOrganizationPolicy;
use App\Policies\ResourceCollectionPolicy;
use App\Policies\ResourcePolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Engagement::class => EngagementPolicy::class,
        RegulatedOrganization::class => RegulatedOrganizationPolicy::class,
        ResourceCollection::class => ResourceCollectionPolicy::class,
        Individual::class => IndividualPolicy::class,
        Meeting::class => MeetingPolicy::class,
        Project::class => ProjectPolicy::class,
        Organization::class => OrganizationPolicy::class,
        Resource::class => ResourcePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('encryptedUserProvider', function ($app, array $config) {
            return new EncryptedUserProvider($app['hash'], $config['model']);
        });

        Gate::define('block', function (User $user) {
            return $user->context === 'individual'
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
    }
}
