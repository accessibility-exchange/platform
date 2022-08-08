<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->morphs('projectable');
            $table->bigInteger('ancestor_id')
                ->references('id')
                ->on('projects')
                ->nullable();
            $table->json('languages')->nullable();
            $table->json('name');
            $table->json('goals')->nullable();
            $table->json('scope')->nullable();
            $table->json('regions')->nullable();
            $table->json('out_of_scope')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('outcome_analysis')->nullable();
            $table->json('outcome_analysis_other')->nullable();
            $table->json('outcomes')->nullable();
            $table->boolean('public_outcomes')->nullable();
            $table->json('team_size')->nullable();
            $table->boolean('team_has_disability_or_deaf_lived_experience')->nullable();
            $table->boolean('team_has_other_lived_experience')->nullable();
            $table->json('team_languages')->nullable();
            $table->json('team_trainings')->nullable();
            $table->boolean('seeking_consultant')->nullable();
            $table->string('consultant_name')->nullable();
            $table->bigInteger('individual_consultant_id')
                ->references('id')
                ->on('individuals')
                ->nullable();
            $table->bigInteger('organizational_consultant_id')
                ->references('id')
                ->on('organizations')
                ->nullable();
            $table->json('consultant_responsibilities')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->boolean('contact_person_vrs')->nullable();
            $table->string('preferred_contact_method')->default('email');
            $table->json('contact_person_response_time')->nullable();
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
};
