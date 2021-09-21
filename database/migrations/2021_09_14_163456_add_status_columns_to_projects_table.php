<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('found_consultants')->nullable();
            $table->boolean('confirmed_consultants')->nullable();
            $table->boolean('scheduled_planning_meeting')->nullable();
            $table->boolean('notified_of_planning_meeting')->nullable();
            $table->boolean('prepared_project_orientation')->nullable();
            $table->boolean('prepared_contractual_documents')->nullable();
            $table->boolean('booked_access_services_for_planning')->nullable();
            $table->boolean('finished_planning_meeting')->nullable();
            $table->boolean('scheduled_consultation_meetings')->nullable();
            $table->boolean('notified_of_consultation_meetings')->nullable();
            $table->boolean('prepared_consultation_materials')->nullable();
            $table->boolean('booked_access_services_for_consultations')->nullable();
            $table->boolean('finished_consultation_meetings')->nullable();
            $table->boolean('prepared_accessibility_plan')->nullable();
            $table->boolean('prepared_follow_up_plan')->nullable();
            $table->boolean('shared_plans_with_consultants')->nullable();
            $table->boolean('published_accessibility_plan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'found_consultants',
                'confirmed_consultants',
                'scheduled_planning_meeting',
                'notified_of_planning_meeting',
                'prepared_project_orientation',
                'prepared_contractual_documents',
                'booked_access_services_for_planning',
                'finished_planning_meeting',
                'scheduled_consultation_meetings',
                'notified_of_consultation_meetings',
                'prepared_consultation_materials',
                'booked_access_services_for_consultations',
                'finished_consultation_meetings',
                'prepared_accessibility_plan',
                'prepared_follow_up_plan',
                'shared_plans_with_consultants',
                'published_accessibility_plan',
            ]);
        });
    }
}
