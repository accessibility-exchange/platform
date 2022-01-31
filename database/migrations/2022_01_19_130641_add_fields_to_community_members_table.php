<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCommunityMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('community_members', function (Blueprint $table) {
            $table->json('bio')->nullable()->change();
            $table->string('locality')->nullable()->change();
            $table->string('region')->nullable()->change();
            $table->string('preferred_contact_method')->change();
            $table->dropColumn([
                'birth_date',
                'creator_relationship',
                'creator_name',
                'support_person_phone',
                'support_person_email',
            ]);
            $table->json('roles');
            $table->boolean('hide_location')->default(false);
            $table->json('other_links')->nullable();
            $table->json('areas_of_interest')->nullable();
            $table->json('service_preference')->nullable();
            $table->string('age_group')->nullable();
            $table->string('living_situation')->nullable();
            $table->json('other_lived_experience')->nullable();
            $table->json('lived_experience')->nullable();
            $table->json('skills_and_strengths')->nullable();
            $table->json('work_and_volunteer_experiences')->nullable();
            $table->json('languages')->nullable();
            $table->json('support_people')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_members', function (Blueprint $table) {
            $table->dropColumn([
                'hide_location',
                'other_links',
                'service_preference',
                'age_group',
                'living_situation',
                'other_lived_experience',
                'lived_experience',
                'skills_and_strengths',
                'work_and_volunteer_experiences',
                'languages',
                'support_people',
            ]);
        });
    }
}
