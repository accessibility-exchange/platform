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
            $table->schemalessAttributes('extra_attributes');
            $table->string('website_link')->nullable();
            $table->json('consulting_services')->nullable();
            $table->json('other_disability_type_connection')->nullable();
            $table->json('other_ethnoracial_identity_connection')->nullable();
            $table->string('connection_lived_experience')->nullable();
            $table->dropColumn(['web_links', 'other_lived_experience_connections', 'other_constituency_connections']);
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
            $table->json('web_links')->nullable();
            $table->json('other_lived_experience_connections')->nullable();
            $table->json('other_constituency_connections')->nullable();
            $table->dropColumn(['website_link', 'consulting_services', 'extra_attributes', 'other_disability_type_connection', 'other_ethnoracial_identity_connection', 'connection_lived_experience']);
        });
    }
};
