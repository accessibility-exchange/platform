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
        Schema::table('individuals', function (Blueprint $table) {
            $table->dropColumn(['creator', 'roles', 'hide_location', 'areas_of_interest', 'service_preference', 'other_lived_experience', 'preferred_contact_methods']);
            $table->renameColumn('work_and_volunteer_experiences', 'relevant_experiences');
            $table->json('working_languages')->nullable();
            $table->json('other_lived_experience_connections')->nullable();
            $table->json('other_constituency_connections')->nullable();
            $table->enum('preferred_contact_method', [
                'email',
                'phone',
                'vrs',
            ])->nullable();
            $table->string('preferred_contact_person')->nullable();
            $table->boolean('vrs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('individuals', function (Blueprint $table) {
            $table->enum('creator', ['self', 'other'])->default('self');
            $table->json('roles');
            $table->boolean('hide_location')->default(false);
            $table->json('service_preference')->nullable();
            $table->json('areas_of_interest')->nullable();
            $table->json('other_lived_experience')->nullable();
            $table->json('preferred_contact_methods')->nullable();
            $table->renameColumn('relevant_experiences', 'work_and_volunteer_experiences');
            $table->dropColumn(['working_languages', 'preferred_contact_method', 'preferred_contact_person', 'vrs', 'other_lived_experience_connections', 'other_constituency_connections']);
        });
    }
};
