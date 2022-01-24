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
            $table->string('locality')->nullable()->change();
            $table->string('region')->nullable()->change();
            $table->json('other_links')->nullable();
            $table->json('service_preference')->nullable();
            $table->string('age_group')->nullable();
            $table->json('other_lived_experience')->nullable();
            $table->json('lived_experience')->nullable();
            $table->json('skills_and_strengths')->nullable();
            $table->json('work_and_volunteer_experiences')->nullable();
            $table->json('communication_with_platform')->nullable();
            $table->json('communication_with_entities')->nullable();
            $table->json('communication_languages')->nullable();
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
            $table->dropColumn([
                'other_links',
                'service_preference',
                'age_group',
                'other_lived_experience',
                'lived_experience',
                'skills_and_strengths',
                'work_and_volunteer_experiences',
                'communication_with_platform',
                'communication_with_entities',
                'communication_languages',
                'meeting_types',
            ]);
        });
    }
}
