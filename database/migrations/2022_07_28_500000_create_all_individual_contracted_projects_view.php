<?php

use App\Models\Individual;
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
            'all_individual_contracted_projects',
            [
                (new Individual)->connectingEngagementProjects(),
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
        Schema::dropView('all_individual_contracted_projects');
    }
};
