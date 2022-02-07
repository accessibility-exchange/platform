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
            $table->dropColumn([
                'birth_date',
                'creator_name',
                'creator_relationship',
                'support_person_phone',
                'support_person_email',
                'preferred_contact_method',
            ]);
            $table->json('roles');
            $table->boolean('hide_location')->default(false);
            $table->json('other_links')->nullable();
            $table->json('areas_of_interest')->nullable();
            $table->json('service_preference')->nullable();
            $table->string('age_group')->nullable();
            $table->boolean('rural_or_remote')->default(false);
            $table->json('other_lived_experience')->nullable();
            $table->json('lived_experience')->nullable();
            $table->json('skills_and_strengths')->nullable();
            $table->json('work_and_volunteer_experiences')->nullable();
            $table->json('languages')->nullable();
            $table->json('support_people')->nullable();
            $table->json('preferred_contact_methods')->nullable();
            $table->json('meeting_types')->nullable();
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
            $table->date('birth_date')->nullable();
            $table->string('creator_name')->nullable();
            $table->json('creator_relationship')->nullable();
            $table->string('support_person_phone')->nullable();
            $table->string('support_person_email')->nullable();
            $table->enum('preferred_contact_method', [
                'phone',
                'support_person_phone',
                'email',
                'support_person_email',
            ])->nullable();
            $table->dropColumn([
                'hide_location',
                'other_links',
                'service_preference',
                'age_group',
                'rural_or_remote',
                'other_lived_experience',
                'lived_experience',
                'skills_and_strengths',
                'work_and_volunteer_experiences',
                'languages',
                'support_people',
                'preferred_contact_methods',
                'meeting_types',
            ]);
        });
    }
}
