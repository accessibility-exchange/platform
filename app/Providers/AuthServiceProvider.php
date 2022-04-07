<?php

namespace App\Providers;

use App\Models\CommunityMember;
use App\Models\Engagement;
use App\Models\Organization;
use App\Models\Project;
use App\Models\RegulatedOrganization;
use App\Models\Resource;
use App\Models\ResourceCollection;
use App\Policies\CommunityMemberPolicy;
use App\Policies\EngagementPolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RegulatedOrganizationPolicy;
use App\Policies\ResourceCollectionPolicy;
use App\Policies\ResourcePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        CommunityMember::class => CommunityMemberPolicy::class,
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
    }
}
