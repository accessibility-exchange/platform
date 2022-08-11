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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->json('name');
            $table->json('slug');
            $table->string('type');
            $table->json('languages')->nullable();
            $table->json('roles')->nullable();
            $table->string('region')->nullable();
            $table->string('locality')->nullable();
            $table->json('about')->nullable();
            $table->json('service_areas')->nullable();
            $table->json('working_languages')->nullable();
            $table->json('consulting_services')->nullable();
            $table->json('social_links')->nullable();
            $table->string('website_link')->nullable();
            $table->schemalessAttributes('extra_attributes');
            $table->json('other_disability_type')->nullable();
            $table->json('other_ethnoracial_identity')->nullable();
            $table->string('staff_lived_experience')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->boolean('contact_person_vrs')->nullable();
            $table->string('preferred_contact_method')->default('email');
            $table->string('preferred_notification_method')->default('email');
            $table->schemalessAttributes('notification_settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
};
