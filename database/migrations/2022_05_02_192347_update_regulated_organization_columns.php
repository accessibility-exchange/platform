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
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->string('locality')->nullable()->change();
            $table->string('region')->nullable()->change();
            $table->json('languages')->nullable();
            $table->json('about')->nullable();
            $table->json('accessibility_and_inclusion_links')->nullable();
            $table->json('social_links')->nullable();
            $table->string('website_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regulated_organizations', function (Blueprint $table) {
            $table->string('locality')->nullable(false)->change();
            $table->string('region')->nullable(false)->change();
            $table->dropColumn(['languages', 'about', 'accessibility_and_inclusion_links', 'social_links', 'website_link']);
        });
    }
};
