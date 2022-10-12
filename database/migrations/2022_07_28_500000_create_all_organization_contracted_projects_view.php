<?php

use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Staudenmeir\LaravelMergedRelations\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::createOrReplaceMergeViewWithoutDuplicates(
            'all_organization_contracted_projects',
            [
                /** TODO: add project and engagement-level consultants.
                (new Organization)->consultingProjects(),
                (new Organization)->consultingEngagementProjects(),
                 **/
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
        Schema::dropView('all_organization_contracted_projects');
    }
};
