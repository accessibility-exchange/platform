<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'start_date',
                'end_date',
                'regions',
                'virtual_consultation',
                'timeline',
                'payment_terms',
                'payment_negotiable',
                'existing_clients',
                'prospective_clients',
                'employees',
                'priority_outreach',
                'locality',
                'location_description',
                'min',
                'max',
                'anything_else',
                'flexible_deadlines',
                'flexible_breaks',
                'found_participants',
                'confirmed_participants',
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
                'shared_plans_with_participants',
                'published_accessibility_plan',
            ]);
            $table->bigInteger('ancestor_id')
                ->references('id')
                ->on('projects')
                ->nullable();
            $table->renameColumn('impact', 'scope');
            $table->json('outcomes')->nullable();
            $table->boolean('public_outcomes')->nullable();
            $table->string('team_size')->nullable();
            $table->boolean('has_consultant')->nullable();
            $table->string('consultant_name')->nullable();
            $table->string('consultant_email')->nullable();
            $table->string('consultant_phone')->nullable();
            $table->json('consultant_responsibilities')->nullable();
            $table->json('team_training')->nullable();
        });

        Schema::table('engagements', function (Blueprint $table) {
            $table->rename('events', 'meetings');
            $table->json('other_reporting')->nullable();
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
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->json('regions')->nullable();
            $table->renameColumn('scope', 'impact');
            $table->boolean('virtual_consultation')->nullable();
            $table->json('timeline')->nullable();
            $table->json('payment_terms')->nullable();
            $table->boolean('payment_negotiable')->nullable();
            $table->boolean('existing_clients')->nullable();
            $table->boolean('prospective_clients')->nullable();
            $table->boolean('employees')->nullable();
            $table->json('priority_outreach')->nullable();
            $table->json('locality')->nullable();
            $table->json('location_description')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->json('anything_else')->nullable();
            $table->boolean('flexible_deadlines')->nullable();
            $table->boolean('flexible_breaks')->nullable();
            $table->boolean('found_participants')->nullable();
            $table->boolean('confirmed_participants')->nullable();
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
            $table->boolean('shared_plans_with_participants')->nullable();
            $table->boolean('published_accessibility_plan')->nullable();
            $table->dropColumn([
                'ancestor_id',
                'outcomes',
                'public_outcomes',
                'team_size',
                'has_consultant',
                'consultant_name',
                'consultant_email',
                'consultant_phone',
                'consultant_responsibilities',
                'team_training',
            ]);
        });

        Schema::table('engagements', function (Blueprint $table) {
            $table->rename('meetings', 'events');
            $table->json('events')->nullable();
        });
    }
};
