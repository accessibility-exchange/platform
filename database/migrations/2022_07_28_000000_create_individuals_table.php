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
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->json('picture_alt')->nullable();
            $table->json('languages')->nullable();
            $table->json('roles')->nullable();
            $table->json('pronouns')->nullable();
            $table->json('bio')->nullable();
            $table->string('region')->nullable();
            $table->string('locality')->nullable();
            $table->json('working_languages')->nullable();
            $table->json('consulting_services')->nullable();
            $table->json('social_links')->nullable();
            $table->string('website_link')->nullable();
            $table->schemalessAttributes('extra_attributes');
            $table->json('other_disability_type_connection')->nullable();
            $table->json('other_ethnoracial_identity_connection')->nullable();
            $table->string('connection_lived_experience')->nullable();
            $table->json('lived_experience')->nullable();
            $table->json('skills_and_strengths')->nullable();
            $table->json('relevant_experiences')->nullable();
            $table->json('meeting_types')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('first_language');
            $table->string('other_payment_type')->nullable();
            $table->json('other_access_need')->nullable();
            $table->string('signed_language_for_interpretation')->nullable();
            $table->string('spoken_language_for_interpretation')->nullable();
            $table->string('signed_language_for_translation')->nullable();
            $table->string('written_language_for_translation')->nullable();
            $table->string('street_address')->nullable();
            $table->string('unit_apartment_suite')->nullable();
            $table->string('postal_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individuals');
    }
};
