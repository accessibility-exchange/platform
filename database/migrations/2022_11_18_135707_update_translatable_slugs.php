<?php

use App\Models\Organization;
use App\Models\RegulatedOrganization;
use App\Models\Resource;
use App\Models\ResourceCollection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $organizations = Organization::all();

        foreach ($organizations as $organization) {
            $organization->generateSlug();
            $organization->save();
        }

        $regulatedOrganizations = RegulatedOrganization::all();

        foreach ($regulatedOrganizations as $regulatedOrganization) {
            $regulatedOrganization->generateSlug();
            $regulatedOrganization->save();
        }

        $resourceCollections = ResourceCollection::all();

        foreach ($resourceCollections as $resourceCollection) {
            $resourceCollection->generateSlug();
            $resourceCollection->save();
        }

        $resources = Resource::all();

        foreach ($resources as $resource) {
            $resource->generateSlug();
            $resource->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $organizations = Organization::all();

        foreach ($organizations as $organization) {
            $organization->generateSlug();
            $organization->save();
        }

        $regulatedOrganizations = RegulatedOrganization::all();

        foreach ($regulatedOrganizations as $regulatedOrganization) {
            $regulatedOrganization->generateSlug();
            $regulatedOrganization->save();
        }

        $resourceCollections = ResourceCollection::all();

        foreach ($resourceCollections as $resourceCollection) {
            $resourceCollection->generateSlug();
            $resourceCollection->save();
        }

        $resources = Resource::all();

        foreach ($resources as $resource) {
            $resource->generateSlug();
            $resource->save();
        }
    }
};
