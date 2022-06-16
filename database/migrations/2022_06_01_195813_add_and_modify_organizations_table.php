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
        Schema::table('organizations', function (Blueprint $table) {
            $table->schemalessAttributes('extra_attributes');
            $table->string('type');
            $table->string('locality')->nullable()->change();
            $table->string('region')->nullable()->change();
            $table->json('languages')->nullable();
            $table->json('working_languages')->nullable();
            $table->json('about')->nullable();
            $table->json('service_areas')->nullable();
            $table->json('area_types')->nullable();
            $table->json('social_links')->nullable();
            $table->string('website_link')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->json('other_disability_type')->nullable();
            $table->enum('staff_lived_experience', ['yes', 'no', 'prefer-not-to-answer']);
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->boolean('contact_person_vrs')->nullable();
            $table->enum('preferred_contact_method', ['phone', 'email'])->nullable();
            $table->dropColumn('area_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('locality')->nullable(false)->change();
            $table->string('region')->nullable(false)->change();
            $table->json('area_types')->nullable();
            $table->dropColumn(['extra_attributes', 'type', 'languages', 'working_languages', 'about', 'service_areas', 'area_types', 'social_links', 'website_link', 'published_at', 'other_disability_type', 'staff_lived_experience']);
        });
    }
};
