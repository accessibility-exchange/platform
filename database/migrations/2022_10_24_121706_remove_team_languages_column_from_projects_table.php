<?php

use App\Models\Individual;
use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Staudenmeir\LaravelMergedRelations\Facades\Schema as MergedRelationSchema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('team_languages');
        });

        MergedRelationSchema::createOrReplaceMergeViewWithoutDuplicates(
            'all_individual_contracted_projects',
            [
                (new Individual)->connectingEngagementProjects(),
            ]
        );

        MergedRelationSchema::createOrReplaceMergeViewWithoutDuplicates(
            'all_organization_contracted_projects',
            [
                (new Organization)->connectingEngagementProjects(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('team_languages')->after('team_has_disability_or_deaf_lived_experience')->nullable();
        });

        MergedRelationSchema::createOrReplaceMergeViewWithoutDuplicates(
            'all_individual_contracted_projects',
            [
                (new Individual)->connectingEngagementProjects(),
            ]
        );

        MergedRelationSchema::createOrReplaceMergeViewWithoutDuplicates(
            'all_organization_contracted_projects',
            [
                (new Organization)->connectingEngagementProjects(),
            ]
        );
    }
};
