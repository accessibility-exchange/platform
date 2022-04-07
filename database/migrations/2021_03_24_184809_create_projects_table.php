<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('regulated_organization_id')
                ->constrained()
                ->onDelete('cascade');
            $table->json('regions')->nullable();
            $table->json('goals')->nullable();
            $table->json('impact')->nullable();
            $table->json('out_of_scope')->nullable();
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
            $table->dateTime('published_at')->nullable();
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
