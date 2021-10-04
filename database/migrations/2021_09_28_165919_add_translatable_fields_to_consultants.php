<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTranslatableFieldsToConsultants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->json('bio')->change();
            $table->json('pronouns')->nullable()->change();
            $table->json('creator_relationship')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultants', function (Blueprint $table) {
            $table->text('bio')->change();
            $table->string('pronouns')->nullable()->change();
            $table->string('creator_relationship')->nullable()->change();
        });
    }
}
